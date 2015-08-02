<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\base;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;

/**
 * Description of Mutex
 *
 * @author Volkov Grigorii
 * 
 * @property-read boolean $IsWindows
 * @property-read boolean $IsAcquired
 * @property-read resource $SemId
 */
class Mutex extends \yii\base\Component
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $filename = '';

    private $sem_id;
    private $is_acquired = false;
    private $is_windows = false;
    private $filepointer;

    public function init()
    {
        Yii::info('Mutex init', self::className().'::init');
        if(is_null($this->id)) {
                throw new InvalidConfigException(self::className().'::id is required.');
        }
//        if(substr(PHP_OS, 0, 3) == 'WIN') {
                $this->is_windows = true;
//        }
        if($this->is_windows) {
                if(empty($this->filename)) {
                        $this->filename = Yii::getAlias('@app/runtime/sem.'.md5($this->id));
                }
        } else {
                if(!($this->sem_id = sem_get($this->id, 1))) {
                        throw ErrorException("Error getting semaphore");
                }
        }
        return parent::init();
    }

    public function acquire()
    {
        Yii::info('Mutex acquired', self::className().'::acquire');
        Yii::beginProfile("Mutex({$this->id}) acquired", self::className().'::acquire');
        if($this->is_windows) {
                if(($this->filepointer = @fopen($this->filename, "w+")) == false) {
                        throw ErrorException("Error opening mutex file");
                }
                if(flock($this->filepointer, LOCK_EX) == false) {
                        throw ErrorException("Error locking mutex file");
                }
        } else {
                if(!sem_acquire($this->sem_id)){
                        throw ErrorException("Error acquiring semaphore");
                }
        }
        Yii::endProfile("Mutex({$this->id}) acquired", self::className().'::acquire');
        $this->is_acquired = true;
        Yii::trace('Mutex locked', self::className().'::acquire');
        Yii::beginProfile("Mutex({$this->id}) critical section", self::className().'::CriticalSection');
        return true;
    }

    public function release()
    {
        if(!$this->is_acquired) {
                return true;
        }
        Yii::endProfile("Mutex({$this->id}) critical section", self::className().'::CriticalSection');
        Yii::info('Mutex released', self::className().'::release');
        Yii::beginProfile("Mutex({$this->id}) released", self::className().'::release');
        if($this->is_windows) {
                if(flock($this->filepointer, LOCK_UN) == false) {
                        throw ErrorException("Error unlocking mutex file");
                }
                fclose($this->filepointer);
        } else {
                if(!sem_release($this->sem_id)) {
                        throw ErrorException("Error releasing semaphore");
                }
        }
        Yii::endProfile("Mutex({$this->id}) released", self::className().'::release');
        $this->is_acquired = false;
        Yii::trace('Mutex unlocked', self::className().'::release');
        return true;
    }

    public function getSemId()
    {
        return $this->sem_id;
    }

    public function getIsWindows()
    {
        return $this->is_windows;
    }

    public function getIsAcquired()
    {
        return $this->is_acquired;
    }
}