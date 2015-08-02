function BaseEngine($scope) {
    this._$scope = $scope;
    this._acts = [];
    this._isActivate = false;
}

BaseEngine.prototype.findTextarea = function(noteId) {
    return $('#note-' + noteId);
};

BaseEngine.prototype.findNote = function(noteId) {
    var r = undefined;
    $.each(this._$scope.notes, function(kn, vn) {
        $.each(vn, function(k, v) {
            if (v.ID == noteId) {
                r = v;
            }
        });
    });
    return r;
};

BaseEngine.prototype.appendNote = function(data) {
    this._$scope.notes[data.ParagraphID].push(data);
};

BaseEngine.prototype.deleteNote = function(noteId) {
    var index = -1;
    var self = this;
    $.each(self._$scope.notes, function(kn, vn) {
        $.each(vn, function(k, v) {
            if (v.ID == noteId) {
                index = k;
            }
        });
        if (index >= 0) {
            self._$scope.notes[kn].splice(index, 1);
            index = -1;
        }
    });
};

BaseEngine.prototype.append = function(cmd) {
    this._acts.push(cmd);
};

BaseEngine.prototype.exec = function(cmd) {
    console.log('===========exec===========');
    console.log(cmd);
    var note = this.findNote(cmd.NoteID);
    if (cmd.Type === 'w') {
        if (cmd.CursorBegin === cmd.CursorEnd) {
            note.Name = note.Name.insertAt(cmd.CursorBegin, cmd.String);
        console.log('inserted');
        } else {
            note.Name = note.Name.replaceAt(cmd.CursorBegin, cmd.CursorEnd, cmd.String);
        console.log('replaced');
        }
        note.NoteActionID = cmd.ID;
        console.log(note.Name);
    }
    if (cmd.Type === 'c') {
        this.appendNote({
            'ID': cmd.NoteID,
            'NoteActionID': cmd.ID,
            'Name': cmd.String,
            'ParagraphID': cmd.ParagraphID
        });
    }
    if (cmd.Type === 'r') {
        this.deleteNote(cmd.NoteID);
    }
};

BaseEngine.prototype.load = function() {
    if (this._isActivate) {
        return;
    }
    var self = this;
    this._isActivate = true;
    var tmp = [];
    $.each(this._$scope.notes, function(kn, vn) {
        $.each(vn, function(k, v) {
            tmp.push(v);
        });
    });
    $.get('/note/bind', {'tid': this._$scope.textId, 'token': this._$scope.token, 'reqs': tmp}, function(data) {
        $.each(data.cmds, function(key, cmd) {
            self.exec(cmd);
        });
        self._isActivate = false;
    }).fail(function() {
        //
    });
};

BaseEngine.prototype.save = function() {
    if (this._acts.length === 0 || this._isActivate) {
        return;
    }
    var self = this;
    this._isActivate = true;
    var tmp = this._acts;
    this._acts = [];
    $.get('/note/save', {'acts': tmp}, function(data) {
        self._isActivate = false;
    }).fail(function() {
        var oldActs = self._acts;
        self._acts = [];
        $.each(tmp, function(v) {
            self._acts.push(v);
        });
        $.each(oldActs, function(v) {
            self._acts.push(v);
        });
    });
};

angular
.module('notes', ['ngResource'])
.controller('notesController', function($scope, $timeout, $interval) {
    var eng = new BaseEngine($scope);
    
    $scope.token = undefined;
    $scope.notes = {};
    
    $scope.createNote = function(paragraphId) {
        $.post('/note/create', {'ParagraphID': paragraphId, 'Name': ''}, function(data) {
//            eng.appendNote(data);
        });
    };
    
    $scope.deleteNote = function(note) {
        $.get('/note/delete', {'id': note.ID}, function(data) {
            eng.deleteNote(note.ID);
        });
    };
    
    $scope.noteKeyDown = function(note, event) {
        if (event.ctrlKey) {
            return;
        }
        var textarea = eng.findTextarea(note.ID);
        var caret = textarea.caret();
        var string = false;
        switch (event.keyCode) {
        case 16: // Shift
        case 17: // Ctrl
        case 18: // Alt
        case 37: // Left
        case 38: // Up
        case 39: // Right
        case 40: // Down
        case 36: // Home
        case 35: // End
        case 45: // Insert
        case 33: // Up
        case 34: // Down
        case 144: // NumLock
        return;
        case 8:  // Backspace
            string = '';
            if (caret.begin === caret.end) {
                --caret.begin;
            }
        break;
        case 46:  // Delete
            string = '';
            if (caret.begin === caret.end) {
                ++caret.end;
            }
        break;
        case 9:  // Tab
            string = '\t';
        break;
        case 13: // Enter
            string = '\n';
        break;
        }
        $timeout(function() {
            if (string === false) {
                string = textarea.val()[caret.begin];
            }
            eng.append({
                UserID: $scope.token,
                NoteID: note.ID,
                Type: 'w',
                CursorBegin: caret.begin,
                CursorEnd: caret.end,
                String: string,
                Timestamp: new Date().getTime()
            });
        }, 1);
    };
    
    $interval(function() {
        eng.save();
    }, 3000);
    
    $interval(function() {
        eng.load();
    }, 5000);
});