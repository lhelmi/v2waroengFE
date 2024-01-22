function validStatusCode(){
    return [
        200, 201
    ];
}

function invalidStatusCode(){
    return [
        403, 401
    ];
}

function notFoundStatusCode(){
    return [
        404
    ];
}

async function fetchData(data, url, method) {
    let body = {
        method: method,
        headers: {"Content-type": "application/json; charset=UTF-8"}
    };
    switch (method) {
        case 'POST':
            body.body = JSON.stringify(data);
        break;

        case 'PUT':
            body.body = JSON.stringify(data);
        break;

        case 'GET':
            body;
        break;

        default:
            body;
        break;
    }
    const res = await fetch(url, body);

    return res;
}

async function setStatus(fetch){
    let temp = await fetch.json();
    temp.status = fetch.status;
    return temp;
}

function setToken (key, value) {
    const now = new Date();
    const item = {
        value: value,
        expiry: now.getTime() + getTTL(),
    }
    localStorage.setItem(key, JSON.stringify(item))
}

function getTTL(){
    return (1000 * 60) * 60;
}

function isLogin(){
    const token = localStorage.getItem("token");
    if(token){
        window.location.href = setUrl('/home');
    }
}

function getWithExpiry(key) {
    const itemStr = localStorage.getItem(key);
    if (!itemStr) {
        return null
    }

    const item = JSON.parse(itemStr)
    const now = new Date()
    if (now.getTime() > item.expiry) {
        console.log(localStorage.getItem("token"));
        localStorage.removeItem(key)
        return null
    }
    console.log(localStorage.getItem("token"));
    return item.value
}

async function sendData(data, url, method){

    const fetch = await fetchData(data, url, method);
    if(notFoundStatusCode().includes(fetch.status)){
        const res = await fetch.json();
        if(res?.internal){
            Swal.fire({
                text: `${res.error}`,
                icon: "error"
            });
        }
        return fetch;
    }

    if(validStatusCode().includes(fetch.status)){
        const res = await setStatus(fetch);
        return res;
    }

    if(invalidStatusCode().includes(fetch.status)){
        const res = await setStatus(fetch);
        return res;
    }

    Swal.fire({
        title: `Error : ${fetch.status}`,
        text: `Pesan Error : ${fetch.statusText}`,
        icon: "error"
    });

    return fetch;


}

function formSerialize(data){
    const res = data.reduce((map, obj) => {
        map[obj.name] = obj.value;
        return map;
    }, {});

    return res;
}

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
