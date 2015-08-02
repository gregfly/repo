angular
.module('notes', ['ngResource'])
.controller('notesController', function($scope, $timeout, $interval) {
    function getTextarea(noteId) {
        return $('#note-'+noteId);
    }
    
    function applyCmd(cmd) {
        var textarea = getTextarea(cmd.n);
        var val = textarea.val();
        if (cmd.Type === 'w') {
            if (cmd.c[0] === cmd.c[1]) {
                val = val.insertAt(cmd.c[0], cmd.s);
            } else {
                val = val.replaceAt(cmd.c[0], cmd.c[1], cmd.s);
            }
        }
        textarea.val(val);
    }
    /*$timeout(function() {
        applyCmd({m:'w',c:[0,0],s:'ww',n:'1'});
        applyCmd({m:'w',c:[7,7],s:'\r\n',n:'1'});
        applyCmd({m:'w',c:[1,6],s:'\t',n:'1'});
    }, 2000);
    $timeout(function() {
        applyCmd({m:'w',c:[0,0],s:'ww',n:'1'});
        applyCmd({m:'w',c:[7,7],s:'\r\n',n:'1'});
        applyCmd({m:'w',c:[1,6],s:'\t',n:'1'});
    }, 6000);*/
    
    var acts = [];
    $scope.token = undefined;
    $scope.notes = {};
    $scope.noteKeyDown = function(note, event) {
        if (event.ctrlKey) {
            return;
        }
        var textarea = getTextarea(note.ID);
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
                caret.end = --caret.begin;
            }
        break;
        case 46:  // Delete
            string = '';
            if (caret.begin === caret.end) {
                caret.end = caret.begin;
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
            var row = {
                UserID: $scope.token,
                NoteID: note.ID,
                Type: 'w',
                CursorBegin: caret.begin,
                CursorEnd: caret.end,
                String: string,
                Timestamp: new Date().getTime()
            };
            console.log(row);
            acts.push(row);
        }, 1);
    };
    var isActivated = false;
    $interval(function() {
        if (acts.length === 0 || isActivated) {
            return;
        }
        isActivated = true;
        var tmp = acts;
        acts = [];
        $.get('/note/save', {'acts': tmp}, function(data) {
            isActivated = false;
        }).fail(function() {
            var oldActs = acts;
            acts = [];
            $.each(tmp, function(v) {
                acts.push(v);
            });
            $.each(oldActs, function(v) {
                acts.push(v);
            });
        });
    });
});