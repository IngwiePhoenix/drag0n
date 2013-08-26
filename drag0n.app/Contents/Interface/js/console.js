if (typeof console  != "undefined") 
    if (typeof console.log != 'undefined')
        console.olog = console.log;
    else
        console.olog = function() {};

console.insert = function(cs, message) {
    console.olog(message);
    $('#console').append('<div class="'+cs+'">'+message+'</div>');
    var objDiv = document.getElementById("console");
	objDiv.scrollTop = objDiv.scrollHeight;
};
console.log = function(who, msg) { console.insert("status", "["+who+"] "+msg); }
console.debug = function(who, msg) { console.insert("debug", "["+who+"] "+msg); }
console.error = function(who, msg) { console.insert("error", "["+who+"] "+msg); }
console.success = function(who, msg) { console.insert("success", "["+who+"] "+msg); }
console.cmd = function(msg) { console.insert("cmd", msg); }
