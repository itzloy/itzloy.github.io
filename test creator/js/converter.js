function uploadFile() {
    var file_data = $('#file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('excelFile', file_data);
    $.ajax({
        url: 'uploadExcel.php', // point to server-side PHP script
        dataType: 'text', // what to expect back from the PHP script
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (response) {
            getFile(response);
        },
        error: function (response) {
            $('#msg').html(response); // display error response from the PHP script
        }
    });
}

function getFile(fileName) {

    /* set up XMLHttpRequest */
    var url = "excels/" + fileName;
    var worksheetNumber = $("#worksheet-number").val();
    var oReq = new XMLHttpRequest();
    oReq.open("GET", url, true);
    oReq.responseType = "arraybuffer";

    oReq.onload = function (e) {
        var arraybuffer = oReq.response;

        /* convert data to binary string */
        var data = new Uint8Array(arraybuffer);
        var arr = new Array();
        for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
        var bstr = arr.join("");

        /* Call XLSX */
        var workbook = XLSX.read(bstr, { type: "binary" });

        /* DO SOMETHING WITH workbook HERE */
        var first_sheet_name = workbook.SheetNames[worksheetNumber - 1];
        /* Get worksheet */
        var worksheet = workbook.Sheets[first_sheet_name];
		
		var a=JSON.stringify(XLSX.utils.sheet_to_json(worksheet, { raw: true }), null, 2);
		console.log(a);
        $("#code").html('<div class="copy-to-clipboard" onClick="copyToClipBoard()" title="Copy to clipboard"><i class="fa fa-clone pull-right"></i><pre class="alert alert-info" id="json-data">' + a +'</pre></div>');
    }

    oReq.send();
}

function copyToClipBoard(){
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($("#json-data").text()).select();
    document.execCommand("copy");
    $temp.remove();
}