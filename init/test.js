//
// 'use strict';
// console.log(this);
// function a () {
//     console.log(this);
// }
// a();
//
// var obj = {
//     a: function () {
//         console.log(this);
//         function z () {
//             console.log(this);
//         }
//         z();
//         z.call(this);
//     }
// }
// obj.a();

// function c(a, b) {
//     var i, result = b, done = false;
//     for (i = b.length - 1; i >= 0; i -= 1) {
//         if(!done) {
//             if(i <= a.length - 1) {
//                 if(a[i] !== b[i]) {
//                     done = true;
//                     console.log(b[i]);
//                     result[i] = 0;
//                     console.log(i);
//                     console.log(a[i]);
//                     console.log(b[i]);
//
//                 } else {
//                     result[i] = a[i] & b[i];
//                 }
//             } else {
//                 if (b[i] === 1) {
//                     done = true;
//                 }
//                 result[i] = 0;
//             }
//         } else {
//             result[i] = 0;
//         }
//     }
//     console.log(result);
// }
//
// b = [1,0,1,1,0,1,0];
// a = [1,0,0,1,0,1];
// c(a,b);
//
// 'use strict';
// var result = 0;
// var myXor = function (myArray, deep) {
//     deep = deep || 0;
//     var newArray, i;//passing array by value, not by reference
//     for (i = 0; i < myArray.length; i += 1) {
//         newArray = myArray.slice(0);
//         result = result ^ myArray[i];//xor for javascript
//         console.log(myArray[i]);
//
//
//     }
//     for (i = 0; i < myArray.length; i += 1) {
//         newArray.splice(i,1);
//         if(newArray.length){
//             // console.log(newArray);
//             result =  myXor(newArray);
//         }
//     }
//     // console.log(myArray);
// };
'use strict';
//
//
// -----------------------------------
// I still have some ideas on problem 3 with bunch of xors. :)
// I have come up with solution that has max complexity O(N/2 + 1).
// Previous one that i sent along with problem #6 has an error.
// If at all interested please review.
var myXor = function (myArray) {
    if (myArray.length%2) {
        console.log(myArray.length/2);
        var i, result = 0;
        for (i = 0; i < myArray.length/2; i += 1) {
            result = result ^ myArray[i * 2];
        }
        return result;
    } else {
        return 0;
    }
};
var q = myXor([3,5,1,4,9]);


console.log(q);
console.log(3^1^9);
// console.log(Math.fmod(4,2));
