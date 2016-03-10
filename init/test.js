
'use strict';
console.log(this);
function a () {
    console.log(this);
}
a();

var obj = {
    a: function () {
        console.log(this);
        function z () {
            console.log(this);
        }
        z();
        z.call(this);
    }
}
obj.a();
