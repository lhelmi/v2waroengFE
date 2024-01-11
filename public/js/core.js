function setUrl(target) {
    if (!target.startsWith('/'))
        target = '/' + target;
    return APP_URL + target;
}

function isNumber(value) {
    return typeof parseInt(value) === 'number';
}

function isUncorrect(value){
    const errorVal = ["", undefined, null, 0];
    return errorVal.includes(value);
}

function formatRupiah(angka) {
    let temp = angka.toString();

    let number_string = temp.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    return rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
}

function rupiahToNumber(value){
    return parseInt(value.split(".").join(""));
}
