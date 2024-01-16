@extends('layouts.app')
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="col-sm-12" id="productList">
    </div>

    <div class="col-12">
        <div class="collapse col-sm-12" id="collapseExample">
            <div class="card card-body" style="height: 800px">
                <div id="reader" style="height: 300px"></div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-2">
        <button type="button" id="start-scanner" class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Buka Scanner
        </button>
    </div>

    <div class="col-12 m-3">
        <form id="searchForm">
            <div class="mb-3">
                <input type="text" class="form-control" id="barcode" name="barcode">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
    </div>

    <div class="card shadow p-3 mb-5 rounded mb-3">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-4">
                    <label for="" class="col-form-label">Total</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control text-end" id="total" name="total" readonly value="0">
                </div>
                <div class="col-4">
                    <label for="" class="col-form-label">Bayar</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control text-end" id="bayar" name="bayar">
                </div>
                <div class="col-4">
                    <label for="" class="col-form-label">Kembalian</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control text-end" id="kembalian" name="kembalian" readonly>
                </div>
                <button type="button" id="removeAll" class="btn btn-danger">Bersihkan Keranjang</button>
            </div>
        </div>
    </div>

</div>
@endsection
@section('js')
<script type="module">
    let productList = [];
    let number = 0;
    const getBodySize = () => {
        const element = $("#reader");

        let height = element.height();
        let width = element.width();

        return {
            height, width
        }
    }

    let config = {
        fps: 30,
        qrbox: {width: 150, height: 80},
        rememberLastUsedCamera: true,
        aspectRatio : 1.333334,
        supportedScanTypes: [0]
    };

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        config,
        false
    );

    async function onScanSuccess(decodedText, decodedResult) {
        if(!isNumber(decodedText)){
            Swal.fire({
                text: `barcode tidak valid : ${decodedText}`,
                icon: "error"
            });
            return;
        }
        $(document).find('#html5-qrcode-button-camera-stop').trigger('click');
        $("#barcode").val(decodedText);
        // closeCamera();
        $("#searchForm").trigger("submit");
    }

    $("#start-scanner").on('click', function() {
        const bodySize = getBodySize();

        if(bodySize.width > 80){
            bodySize.width = parseInt(bodySize.width*0.8);
            bodySize.height = parseInt(bodySize.height*0.5);
        }

        config.qrbox.height = bodySize.height;
        config.qrbox.width = bodySize.width;

        const element = $(this);
        if(element.text() == "Tutup Scanner"){
            element.text("Buka Scanner")
            $(document).find('#html5-qrcode-button-camera-stop').trigger('click');
            //pause
            // let shouldPauseVideo = true;
            // let showPausedBanner = false;
            // html5QrcodeScanner.pause(shouldPauseVideo, showPausedBanner);
            // html5QrcodeScanner.clear();

        }else{
            $("renderCollapse").toggle('show');
            html5QrcodeScanner.render(onScanSuccess);
            element.text("Tutup Scanner");
        }
    });

    const closeCamera = () => {
        $("#start-scanner").trigger('click');
        $(document).find('#html5-qrcode-button-camera-stop').trigger('click');
    };

    const getProduct = async (barcode) => {
        // const res = await fetch(setUrl(`/product/barcode/${barcode}`));
        const res = await fetch(`${APP_BE_URL}api/public/product?params=${barcode}`);
        return res;
    }

    $("#searchForm").on("submit", async function(e){
        e.preventDefault();
        const barcode = $("#barcode").val();
        const res = await getProduct(barcode);
        if(res.status !== 200){
            Swal.fire({
                text: `${res.statusText}`,
                icon: "error"
            });
            return;
        }
        const product = await res.json();
        const add = addProduct(product.data);

        if(add.isExist){
            updateProduct(add.data);
        }else{
            number += 1;
            const card = productCard(number, product.data.barcode, product.data.name, product.data.price);
            $("#productList").append(card);
        }
        $("#barcode").focus();
        window.scrollTo(0, document.body.scrollHeight);
        sumTotal();
    });

    const getProductList = (data) => {
        let res = [];
        productList.forEach(element => {
            if(element.barcode == data.barcode){
                res.push(element);
            }
        });

        return res;
    }

    const updateProduct = (data) => {
        $("#productList .cardMain").map(function(){
            const element = $(this);
            const code = element.attr('data-code');
            if(data.barcode == code){
                let quantity = element.find($(".quantity"));
                let val = parseInt(quantity.val());
                val = parseInt(val + 1);
                quantity.val(val);
            }
        });
    }

    const addProduct = (data) => {
        let current = []
        current = getProductList(data);
        if(current.length > 0){
            return {
                isExist : true,
                data : current[0]
            }
        }

        data.index = number;
        productList.push(data);
        return {
            isExist : false,
            data : current[0]
        }
    }

    const removeProduct = async (data) => {
        let current = []

        current = getProductList(data);
        if(current.length == 0){
            Swal.fire({
                text: "Data tidak ditemukan?",
                icon: "question"
            });
            return false;
        }

        let is_remove = false;
        await Swal.fire({
            title: "Apakah anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText : "Tidak",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                productList.splice(current[0].index, 1);
                Swal.fire({
                    title: "Produk terhapus!",
                    timer: 2000,
                    icon: "success"
                });
                is_remove = true;
            }
        });
        return is_remove;

    }

    const sumTotal = () => {
        let total = 0;
        $("#productList .card-body").map(function(){
            const element = $(this);
            const price = rupiahToNumber(element.find($(".price")).val());
            const quantity = element.find($(".quantity")).val();

            const grandTotalVal = price*quantity;
            const grandtotal = element.find($(".total"));
            grandtotal.val(formatRupiah(grandTotalVal));
            total += grandTotalVal;
        });

        $("#total").val(formatRupiah(total));

    }

    $(document).on('click', '.btnDeleteProduct', async function () {
        const card = $(this).parent('.card-body');
        const barcode = card.children('.row').find('.barcode').val();
        const temp = {
            barcode : barcode
        }
        const remove = await removeProduct(temp);

        if(remove){
            number -= 1;
            card.parent('.cardMain').remove();
        }
        sumTotal();
    });



    const productCard = (number, barcode, name, price) => {
        let row = "";
        return row += `<div class="card cardMain text-white bg-secondary shadow p-3 mb-5" data-code="${barcode}">
            <div class="row card-header">${number}. ${name}</div>
            <div class="card-body">
                <button type="button" class="btn btn-warning col-12 mb-2 btnDeleteProduct">Hapus</button>

                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label for="" class="col-form-label">Barcode</label>
                    </div>
                    <div class="col-8">
                        <input type="text" readonly class="form-control barcode text-end" value="${barcode}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label for="" class="col-form-label">Harga</label>
                    </div>
                    <div class="col-8">
                        <input type="text" readonly class="form-control price text-end" value="${formatRupiah(price)}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label for="" class="col-form-label">Jumlah</label>
                    </div>
                    <div class="col-8">
                        <input type="number" class="form-control quantity text-end" value="${1}">
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-4">
                        <label for="" class="col-form-label">Total</label>
                    </div>
                    <div class="col-8">
                        <input type="text" readonly class="form-control total text-end">
                    </div>
                </div>
            </div>
        </div>`
    }

    $("#removeAll").on("click", function(){
        Swal.fire({
            title: "Apakah anda yakin?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText : "Tidak",
            confirmButtonText: "Ya, Hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                $("#productList .cardMain").map(function(){
                    const element = $(this);
                    element.remove();
                    productList = [];
                    number = 0;
                    sumTotal();
                    $("#bayar").trigger('keyup');
                    Swal.fire({
                        title: "Produk terhapus!",
                        timer: 2000,
                        icon: "success"
                    });
                });
            }
        });
    });

    $(document).on('change', '.quantity', function () {
        sumTotal();
    });

    $("#bayar").on("keyup", function(){
        $(this).val(formatRupiah($(this).val()))

        let total = $("#total").val();
        if(isUncorrect(total)) total = 0;

        let value = rupiahToNumber($(this).val());
        if(!isNumber(value)) value = 0;

        let kembalian = 0;

        kembalian = value - parseInt(rupiahToNumber(total));
        let minus = '';
        if(kembalian < 0) minus = "-";
        kembalian = `${minus}${formatRupiah(kembalian)}`;

        $("#kembalian").val(kembalian);
    });

</script>
@endsection
