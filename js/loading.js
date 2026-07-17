console.log("Loading JS");
console.log(mode);

setTimeout(function () {

    window.location.href = "result.php?mode=" + mode;

}, 2000);