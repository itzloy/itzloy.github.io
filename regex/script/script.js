document.getElementById("clear").addEventListener("click", clearFunction);

function clearFunction() {
    document.getElementById("conversionString").value="";
}

document.getElementById("convert").addEventListener("click", convertFunction);
function convertFunction() {
    var strRaw = new Array();
    var hostString = "";

    //strRaw = document.getElementById("conversionString").value;
    
    

    var flag = 0;
    
    var subDmncb = document.getElementById("matchPattern").value;
    var anyPathcb = document.getElementById("matchPattern").value;
    var anySubPthcb = document.getElementById("matchPattern").value;
    var hostcb = document.getElementById("matchPattern").value;
    var revseMat =document.getElementById("matchPattern").value;
    
    var revseMat = document.getElementById("inverse").checked;
    var partHostString = "";



    strRawEle = document.getElementsByClassName("match_font");
    for(var c=0; c<strRawEle.length; c++){
        strRaw = strRawEle[c].value;
        flag = 0;
    
        if(subDmncb=="subdmn"){
            var dotCount = 0;//, flag = 0;
            var dotCountIdx = new Array();
            hostString = ".*";
            // Count number of dots in a string.
            for(var i=0; i<strRaw.length; i++){
                if(strRaw[i]=='/' && flag==1){
                    dotCountIdx[dotCount] = i;
                    break;
                }
                else if(strRaw[i]=='?' && flag==1){
                    dotCountIdx[dotCount] = i;
                    break;
                }
                else if(strRaw[i]=='.'){
                    dotCountIdx[dotCount] = i;
                    dotCount++;
                    flag = 1;
                }
            }
            //alert(dotCountIdx);

            /*var lastPartStr = strRaw.substring(dotCountIdx[dotCountIdx.length-2]+1,dotCountIdx[dotCountIdx.length-1]);

            if(lastPartStr.length==2){
                var secLastPartStr = strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1,dotCountIdx[dotCountIdx.length-2])
                if(secLastPartStr.length==2){
                    hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-4]+1);
                }
                else{
                    hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1);   
                }
            }
            else{
                hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1);   
            }*/
            
            var firstPartStr = strRaw.substring(0,dotCountIdx[0])
            
            if(dotCount<3){
                if(firstPartStr.indexOf("://")!=-1 && firstPartStr.indexOf("www")!=-1){
                    hostString+=strRaw.substring(dotCountIdx[0]+1);
                }
                else if(firstPartStr=="www"){
                    hostString+=strRaw.substring(dotCountIdx[0]+1);
                }
                else{
                    hostString+=strRaw.substring(0);
                }
            }
            else{
                var lastPartStr = strRaw.substring(dotCountIdx[dotCount-1]+1,dotCountIdx[dotCount]);
                //alert(lastPartStr);
                if(lastPartStr.length==2){
                    var secondLastPart = strRaw.substring(dotCountIdx[dotCount-2]+1,dotCountIdx[dotCount-1]);
                    //alert(secondLastPart);
                    if(secondLastPart.length==2){
                        hostString+=strRaw.substring(dotCountIdx[dotCount-3]+1);
                    }
                    else{
                        hostString+=strRaw.substring(dotCountIdx[dotCount-2]+1);
                    }
                }
                else{
                    hostString+=strRaw.substring(dotCountIdx[dotCount-2]+1);
                }
            }

            var hostStringArr = new Array();
            hostStringArr = hostString;
            hostString = "";
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='.' && i>1){
                    hostString+="\\.";    
                }
                else if(hostStringArr[i]=='?' && i>1){
                    hostString+="\\?";    
                }
                else if(hostStringArr[i]=='+' && i>1){
                    hostString+="\\+";    
                }
                else if(hostStringArr[i]=='/' && i>1){
                    hostString+="\\/";    
                }
                else{
                    hostString+=hostStringArr[i];
                }
            }
        }
        else if(anyPathcb=="anypth"){
            var pathStartIdx = 0;
            for(var i=0; i<strRaw.length; i++){
                if(strRaw[i]=='/' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(strRaw[i]=='?' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(strRaw[i]=='.'){
                    flag = 1;   
                }
            }
            if(pathStartIdx==0){
                pathStartIdx = strRaw.length;
            }
            hostString+=strRaw.substring(0,pathStartIdx);
            var hostStringArr = new Array();
            hostStringArr = hostString;
            hostString = "";
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='.'){
                    hostString+="\\.";    
                }
                else if(hostStringArr[i]=='?'){
                    hostString+="\\?";    
                }
                else if(hostStringArr[i]=='+'){
                    hostString+="\\+";    
                }
                else if(hostStringArr[i]=='/'){
                    hostString+="\\/";    
                }
                else{
                    hostString+=hostStringArr[i];
                }
            }
            hostString+=".*";
        }
        else if(anySubPthcb=="subdmnpth"){
            var dotCount = 0;
            // flag = 0;
            var dotCountIdx = new Array();
            //hostString = ".*";
            hostString = "";
            // Count number of dots in a string.
            for(var i=0; i<strRaw.length; i++){
                if(strRaw[i]=='/' && flag==1){
                    dotCountIdx[dotCount] = i;
                    break;
                }
                else if(strRaw[i]=='?' && flag==1){
                    dotCountIdx[dotCount] = i;
                    break;
                }
                else if(strRaw[i]=='.'){
                    dotCountIdx[dotCount] = i;
                    dotCount++;
                    flag = 1;
                }
            }
            /*var lastPartStr = strRaw.substring(dotCountIdx[dotCountIdx.length-2]+1,dotCountIdx[dotCountIdx.length-1]);
            
            if(lastPartStr.length==2){
                var secLastPartStr = strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1,dotCountIdx[dotCountIdx.length-2])
                if(secLastPartStr.length==2){
                    hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-4]+1);
                }
                else{
                    hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1);   
                }
            }
            else{
                hostString+= strRaw.substring(dotCountIdx[dotCountIdx.length-3]+1);   
            }*/
            var firstPartStr = strRaw.substring(0,dotCountIdx[0])
            
            if(dotCount<3){
                if(firstPartStr.indexOf("://")!=-1 && firstPartStr.indexOf("www")!=-1){
                    hostString+=strRaw.substring(dotCountIdx[0]+1);
                }
                else if(firstPartStr=="www"){
                    hostString+=strRaw.substring(dotCountIdx[0]+1);
                }
                else{
                    hostString+=strRaw.substring(0);
                }
            }
            else{
                var lastPartStr = strRaw.substring(dotCountIdx[dotCount-1]+1,dotCountIdx[dotCount]);
                //alert(lastPartStr);
                if(lastPartStr.length==2){
                    var secondLastPart = strRaw.substring(dotCountIdx[dotCount-2]+1,dotCountIdx[dotCount-1]);
                    //alert(secondLastPart);
                    if(secondLastPart.length==2){
                        hostString+=strRaw.substring(dotCountIdx[dotCount-3]+1);
                    }
                    else{
                        hostString+=strRaw.substring(dotCountIdx[dotCount-2]+1);
                    }
                }
                else{
                    hostString+=strRaw.substring(dotCountIdx[dotCount-2]+1);
                }
            }


            var hostStringArr = new Array();
            hostStringArr = hostString;
            hostString = "";
            flag = 0;
           
            var pathStartIdx = 0;
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='/' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(hostStringArr[i]=='?' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(hostStringArr[i]=='.'){
                    flag = 1;   
                }
            }
            if(pathStartIdx==0){
                pathStartIdx = hostStringArr.length;
            }
            hostString+=hostStringArr.substring(0,pathStartIdx);
            hostStringArr = hostString;
            hostString = ".*";
            
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='.' && i>1){
                    hostString+="\\.";    
                }
                else if(hostStringArr[i]=='?' && i>1){
                    hostString+="\\?";    
                }
                else if(hostStringArr[i]=='+' && i>1){
                    hostString+="\\+";    
                }
                else if(hostStringArr[i]=='/' && i>1){
                    hostString+="\\/";    
                }
                else{
                    hostString+=hostStringArr[i];
                }
            }
            hostString+=".*";
        }
        else if(hostcb=="host"){
            hostString = "";
            // Count number of dots in a string.
            var checkStrStart = strRaw.indexOf("//:");
            var checkWWWin = strRaw.indexOf("www");
            if(checkStrStart!=-1){
                if(checkWWWin!=-1){
                    hostString+=strRaw.substring(checkWWWin+4);
                }
                else{
                    hostString+=strRaw.substring(checkStrStart+3);
                }
            }
            else if(checkWWWin!=-1){
                hostString+=strRaw.substring(checkWWWin+4);
            }
            else{
                    hostString+=strRaw.substring(0);
            }
            var hostStringArr = new Array();
            hostStringArr = hostString;
            hostString = "";
            flag = 0;
            
            var pathStartIdx = 0;
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='/' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(hostStringArr[i]=='?' && flag==1){
                    pathStartIdx = i;
                    break;
                }
                else if(hostStringArr[i]=='.'){
                    flag = 1;
                }
            }
            if(pathStartIdx==0){
                pathStartIdx = hostStringArr.length;
            }
            hostString+=hostStringArr.substring(0,pathStartIdx);
            hostStringArr = hostString;
            hostString = ".*";
            for(var i=0; i<hostStringArr.length; i++){
                if(hostStringArr[i]=='.' && i>1){
                    hostString+="\\.";    
                }
                else if(hostStringArr[i]=='?' && i>1){
                    hostString+="\\?";    
                }
                else if(hostStringArr[i]=='+' && i>1){
                    hostString+="\\+";    
                }
                else if(hostStringArr[i]=='/' && i>1){
                    hostString+="\\/";    
                }
                else{
                    hostString+=hostStringArr[i];
                }
            }
            hostString+=".*";
        }
        else{
            for(var i=0; i<strRaw.length; i++){
                if(strRaw[i]=='.'){
                    hostString+="\\.";    
                }
                else if(strRaw[i]=='?'){
                    hostString+="\\?";    
                }
                else if(strRaw[i]=='+'){
                    hostString+="\\+";    
                }
                else if(strRaw[i]=='/'){
                    hostString+="\\/";    
                }
                else{
                    hostString+=strRaw[i];
                }
            }
        }

        if(c<1){
            partHostString = partHostString+"("+hostString+")";
            hostString = "";
        }
        else{
            partHostString = partHostString+"|("+hostString+")";
            hostString = "";
        }

    }
    
    if(revseMat==true){
        var revStr = "^((?!("+partHostString+")).)*$";
        partHostString = revStr;
    }
    
    document.getElementById("resultString").getElementsByTagName("label")[0].innerHTML = partHostString;
}