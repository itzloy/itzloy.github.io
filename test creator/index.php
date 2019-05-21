<!DOCTYPE html>
<html>
<head>
    <title>Bulk Test Creator</title>
    <link rel="icon" href="./favicon.png" type="image/gif" sizes="16x16">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="js/xlsx.full.min.js"></script>
   <!-- <script src="js/converter.js"></script>-->
    <link rel="stylesheet" type="text/css" href="loading-bar/loading-bar.css" />
    <script type="text/javascript" src="loading-bar/loading-bar.js"></script>
</head>
<body>
    <header class="header">
        <div class="header-inner clearfix">
            <div class="logo">
                <a title="Home" href="http://www.catchpoint.com">
              <img src="http://theme.zdassets.com/theme_assets/540700/5503c921e70bb0ed4660ab13454fb4eb0d0b06d2.png" alt="Logo">
          </a>
            </div>
        </div>
    </header>
    <br>
    <div id="wrapper" class="container">
        <fieldset>
            <div class="form-group">
                <div class="col-md-6">
                    <label class="control-label" for="Key">Consumer Key</label>
                    <input name="Key" class="form-control" placeholder="Enter Key" type="text" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label class="control-label" for="Secret">Secret</label>
                    <input name="Secret" class="form-control" placeholder="Enter Secret" type="text" value="">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label class="control-label" for="file">Select a file</label>
                    <input name="file" id="file" class="form-control" type="file" style="padding: 4px">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label class="control-label" for="WorkSheet">Select WorkSheet</label>
                    <input id="worksheet-number" name="workSheet" class="form-control" placeholder="WorkSheet number" type="text" value="1">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="progress">
                        <div class="progress-bar" id ="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                            <span class="sr-only">50% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="form-group">
                <div class="col-md-4 text-center col-md-offset-4">
                    <button id="submitBtn" class="btn btn-primary btn-lg btn-block info">Create</button>
                    <!--<button onclick="uploadFile(this);" id="submitBtn" class="btn btn-primary btn-lg btn-block info">Create</button>-->
                </div>
				<div class="col-md-4 text-center col-md-offset-4">
                   <a href="./excels/template.xlsx" download> <button id="submitBtn" class="btn btn-primary btn-lg btn-block info " download><i class="fa fa-download"></i>Template</button></a>
                    <!--<button onclick="uploadFile(this);" id="submitBtn" class="btn btn-primary btn-lg btn-block info">Create</button>-->
                </div>
            </div>
        </fieldset>
        <div class="row">
            <div id="code"></div>
        </div>
    </div>
    <div class="sfwa_footer_area">
        <aside id="text-5" class="widget widget_text">
            <div class="textwidget">
                <div class="col-md-4">
                    <p>© 2018 Catchpoint Systems, Inc</p>
                </div>
            </div>
        </aside>
    </div>
    <script>
        // variable holds data for csv file
        var CSVDataArray = [];

        document.getElementById("submitBtn").addEventListener("click", uploadFile);
        
        function createCORSRequest(method, url) {
          var xhr = new XMLHttpRequest();

          if ("withCredentials" in xhr) {
            // Check if the XMLHttpRequest object has a "withCredentials" property.
            // "withCredentials" only exists on XMLHTTPRequest2 objects.
            xhr.open(method, url, true);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
          } else if (typeof XDomainRequest != "undefined") {
            // Otherwise, check if XDomainRequest.
            // XDomainRequest only exists in IE, and is IE's way of making CORS requests.
            xhr = new XDomainRequest();
            xhr.open(method, url);
            xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded")
          } else {
            // Otherwise, CORS is not supported by the browser.
            xhr = null;
          }
          return xhr;
        }

        function getToken(testDetailsJson){
            var xhr = createCORSRequest('POST', 'https://io.catchpoint.com/ui/api/token');
            if (!xhr) {
              throw new Error('CORS not supported');
            }

            xhr.onload = function() {
             var responseText = xhr.responseText;
             // extract the token from response, please install the plugin for this to work [cross domain issue]
             var token = responseText.substring("18",responseText.indexOf("token_type")-4)
             console.log(token);
             console.log(responseText);
             // process the response.
             // convert the token to base 64:
             var base64= window.btoa(token);
             console.log(base64);
            CreateTestAjaxInitialize("POST", "https://io.catchpoint.com/ui/api/v1/tests/0", base64, testDetailsJson);
            };
            // get the key and secret
            var key= document.getElementsByClassName("form-control")[0].value;
            var secret= document.getElementsByClassName("form-control")[1].value;
            // generate the token
            xhr.send("grant_type=client_credentials&client_id="+key+"&client_secret="+secret);   
}

function CreateTestAjaxInitialize(method, url, base64, testDetailsJson) {

    testDetailsJsonObj = JSON.parse(testDetailsJson);
    //for(x=0; x<testDetailsJsonObj.length; x++){
    //console.log(testDetailsJsonObj[x]["Test Name"]);
        var currentProgressBarStatus = 0;

        var x = 0;
        // set UTC time and date
        var hours = new Date().getUTCHours();
        var suf = "AM";
        var hour = hours;
        if(hours>12){
        hour = hours-12;
        suf = "PM";
        }
        utcNow = new Date().getUTCMonth()+1+"/"+new Date().getUTCDate()+"/"+new Date().getUTCFullYear()+" "+hour+":"+new Date().getUTCMinutes()+":"+new Date().getUTCSeconds()+" "+ suf;


        //This fuction will create a test using ajax in every 7 seconds
        var intervalId = setInterval(function(){
        var xhr = new XMLHttpRequest();

        if ("withCredentials" in xhr) {
            // Check if the XMLHttpRequest object has a "withCredentials" property.
            // "withCredentials" only exists on XMLHTTPRequest2 objects.
            xhr.open(method, url, true);
            xhr.setRequestHeader("Accept","application/json");
            xhr.setRequestHeader("Authorization","Bearer "+base64);
        } else if (typeof XDomainRequest != "undefined") {
            // Otherwise, check if XDomainRequest.
            // XDomainRequest only exists in IE, and is IE's way of making CORS requests.
            xhr = new XDomainRequest();
            xhr.open(method, url);
            xhr.setRequestHeader("Accept","application/json");
            xhr.setRequestHeader("Authorization","Bearer "+base64);
        } else {
            // Otherwise, CORS is not supported by the browser.
            xhr = null;
        }

        // Get the Status ID
        if(testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]=="Active"){
            var activeId=0;
        }else{
            var activeId=1;
        }

        switch(testDetailsJsonObj[x]["Test Type"]){

            case "Web":
            // Var 'data' should contain the JSON file to be posted
            var data = '{ "id":0, "name":"'+testDetailsJsonObj[x]["Test Name"]+'", "test_type":{ "id":0, "name":"Web" }, "division_id":'+testDetailsJsonObj[x]["Division ID"]+', "product_id":'+testDetailsJsonObj[x]["Product ID"]+',"parent_folder_id": '+testDetailsJsonObj[x]["Parent Folder ID"]+', "status":{ "id":'+activeId+', "name":"'+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]+'" }, "start":"'+utcNow+'", "monitor_version":{ "version_type":{ "id":1, "name":"Stable" }, "major_version_number":59 }, "monitor":{ "id":'+testDetailsJsonObj[x]["Monitor Type Id[Refer Appendix]"]+', "name":"'+testDetailsJsonObj[x]["Monitor Name"]+'" }, "http_method":{ "id":0, "name":"Get" }, "advanced_settings":{ "inheritance_type":{ "id":0, "name":"Inherit" } }, "test_url":"'+testDetailsJsonObj[x]["URL[Needs to be specified for Web Tests. Make sure to include protocol]"]+'", "request_section":{ "inheritance_type":{ "id":0, "name":"Inherit" } }, "schedule_section":{ "inheritance_type":{ "id":0, "name":"Inherit" } }, "insight_section":{ "inheritance_type":{ "id":0, "name":"Inherit" } }, "alert_section":{ "inheritance_type":{ "id":0, "name":"Inherit" } }, "change_date":"'+utcNow+'" }';
            break;

            case "DNS Direct":
            var data = '{"id":0,"name":"'+testDetailsJsonObj[x]["Test Name"]+'","test_type":{"id":5,"name":"DNS"},"division_id":'+testDetailsJsonObj[x]["Division ID"]+',"product_id":'+testDetailsJsonObj[x]["Product ID"]+',"parent_folder_id": '+testDetailsJsonObj[x]["Parent Folder ID"]+',"status":{"id":'+activeId+',"name":"'+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]+'"},"start":"'+utcNow+'","monitor":{"id":13,"name":"Direct"},"dns_query_type":{"id":1,"name":"A"},"dns_server":"'+testDetailsJsonObj[x]["DNS Server[Needs to be specified for DNS Direct Tests]"]+'","advanced_settings":{"inheritance_type":{"id":1,"name":"Override"}},"test_domain":"'+testDetailsJsonObj[x]["Domain[Needs to be specified for DNS Direct, DNS Experience, trace and ping Tests]"]+'","protocol":{"id":'+testDetailsJsonObj[x]["Protocol [for DNS direct and experience]"]+',"name":"Udp"},"schedule_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"alert_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"change_date":"'+utcNow+'"}';
            console.log("Status: "+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]);
            console.log("Id: "+activeId);
            break;

           case "DNS Experience":
            var data = '{"id":0,"name":"'+testDetailsJsonObj[x]["Test Name"]+'","test_type":{"id":5,"name":"DNS"},"division_id":'+testDetailsJsonObj[x]["Division ID"]+',"product_id":'+testDetailsJsonObj[x]["Product ID"]+',"parent_folder_id": '+testDetailsJsonObj[x]["Parent Folder ID"]+',"status":{"id":'+activeId+',"name":"'+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]+'"},"start":"'+utcNow+'","monitor":{"id":12,"name":"Experience"},"dns_query_type":{"id":1,"name":"A"},"advanced_settings":{"inheritance_type":{"id":1,"name":"Override"}},"test_domain":"'+testDetailsJsonObj[x]["Domain[Needs to be specified for DNS Direct, DNS Experience, trace and ping Tests]"]+'","protocol":{"id":'+testDetailsJsonObj[x]["Protocol [for DNS direct and experience]"]+',"name":"Udp"},"schedule_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"alert_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"change_date":"'+utcNow+'"}';
            console.log("Status: "+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]);
            console.log("ID: "+activeId);
            break;

            case "Trace Route":
            var data = '{"id":0,"name":"'+testDetailsJsonObj[x]["Test Name"]+'","test_type":{"id":12,"name":"Trace Route"},"division_id":'+testDetailsJsonObj[x]["Division ID"]+',"product_id":'+testDetailsJsonObj[x]["Product ID"]+',"parent_folder_id": '+testDetailsJsonObj[x]["Parent Folder ID"]+',"status":{"id":'+activeId+',"name":"'+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]+'"},"start":"'+utcNow+'","monitor":{"id":'+testDetailsJsonObj[x]["Monitor Type Id[Refer Appendix]"]+',"name":"Trace Route ICMP"},"advanced_settings":{"inheritance_type":{"id":1,"name":"Override"}},"test_location":"'+testDetailsJsonObj[x]["Domain[Needs to be specified for DNS Direct, DNS Experience, trace and ping Tests]"]+'","schedule_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"alert_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"change_date":"'+utcNow+'"}';
            break;

            case "Ping":
            var data = '{"id":0,"name":"'+testDetailsJsonObj[x]["Test Name"]+'","test_type":{"id":6,"name":"Ping"},"division_id":'+testDetailsJsonObj[x]["Division ID"]+',"product_id":'+testDetailsJsonObj[x]["Product ID"]+',"parent_folder_id": '+testDetailsJsonObj[x]["Parent Folder ID"]+',"status":{"id":'+activeId+',"name":"'+testDetailsJsonObj[x]["Status [wether the test should be active or inactive when setup]"]+'"},"start":"'+utcNow+'","monitor":{"id":'+testDetailsJsonObj[x]["Monitor Type Id[Refer Appendix]"]+',"name":"Ping ICMP"},"advanced_settings":{"inheritance_type":{"id":0,"name":"Inherit"}},"test_location":"'+testDetailsJsonObj[x]["Domain[Needs to be specified for DNS Direct, DNS Experience, trace and ping Tests]"]+'","schedule_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"alert_section":{"inheritance_type":{"id":0,"name":"Inherit"}},"change_date":"'+utcNow+'"}';
            break;
        }

        xhr.onload = function() {
             // progess bar update.
        var progressBar = document.getElementById("progress-bar");
             //currentProgressBarStatus
             var eachBarIncrement = 100/testDetailsJsonObj.length;
             currentProgressBarStatus += eachBarIncrement;
             progressBar.setAttribute("style", "width:"+currentProgressBarStatus+"%");

            // adding logs on the page. 
            var logElement = document.getElementsByClassName("downloadCSV");
            var node = document.createElement("div");
            var respSummary=JSON.parse(xhr.responseText);
            if(respSummary.status){

                var textnode = document.createTextNode(x+"."+respSummary.id+" "+testDetailsJsonObj[x-1]["Test Name"]+" "+respSummary.status);
                node.appendChild(textnode);
                logElement[0].appendChild(node);
                // Create array for csv data
                //CSVDataArray[x] = "['"+x+"','"+respSummary.id+"','"+testDetailsJsonObj[x]["Test Name"]+"','"+respSummary.status+"']";
                CSVDataArray[x] = x+","+respSummary.id+","+testDetailsJsonObj[x-1]["Test Name"]+","+respSummary.status;
            }
            else{
                var textnode = document.createTextNode(x+"."+testDetailsJsonObj[x-1]["Test Name"]+": "+respSummary.Message);
                node.appendChild(textnode);
                logElement[0].appendChild(node);
                // Create array for csv data
                //CSVDataArray[x] = "['"+x+"','"+testDetailsJsonObj[x]["Test Name"]+"','"+respSummary.Message+"']";
                CSVDataArray[x] = x+",N/A,"+testDetailsJsonObj[x]["Test Name"]+","+respSummary.Message;
                
            }
        };

        console.log(CSVDataArray[x]);
        //The following call will create the test in the portal using ajax request by passing JSON data
        xhr.send(data);

        x++;
        if(x==testDetailsJsonObj.length){
            clearInterval(intervalId);
        }
        },7000)
    //}
}

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
		
		var testDetailsJson=JSON.stringify(XLSX.utils.sheet_to_json(worksheet, { raw: true }), null, 2);
		//console.log(testDetailsJson);
        getToken(testDetailsJson);
        $("#code").html('<pre class="alert alert-info" id="json-data"><div class="downloadCSV" id="downloadCSV" onclick="downloadCsv()" title="downloadCsv"><i class="fa fa-download pull-right"></i><br/>Logs</div></pre>');
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


function downloadCsv(){
    var convertedCSVDataArray = [];
    for(var i = 1; i < CSVDataArray.length; i++){
     convertedCSVDataArray.push(CSVDataArray[i]);
    }

    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Serial No, Test ID, Test Name, Status\r\n";
    for(var i=0; i<convertedCSVDataArray.length; i++){
       //var eachRow = convertedCSVDataArray[i].replace(/'/g, '');
       //eachRow = eachRow.replace(/\[/g, '');
       //eachRow = eachRow.replace(/\]/g, '');
       //csvContent += eachRow + "\r\n";
       csvContent += convertedCSVDataArray[i]+"\r\n";
    }
    var encodedUri = encodeURI(csvContent);
    window.open(encodedUri);
}
    </script>
</body>
</html>





