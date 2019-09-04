function updateStatus() {
    $.ajax({
        type: 'post',
        url: './ffmpeg/index/0/status',
        success: function(val) {
            var jsonDecode = JSON.parse(val);
            var cpu = "CPU: " + jsonDecode.cpu + "%";
            var mem = "MEM: " + jsonDecode.mem;
            //           var net = "ETH0 " + jsonDecode.net.eth0 + " " + "ETH1: " + jsonDecode.net.eth1;
            var net = "NETWORK: " + jsonDecode.net;


            $('#sysinfo').text(cpu + " " + mem + " " + net);
        }
    });

}

function getActiveStreams() {
    $.ajax({
        type: 'post',
        url: './ffmpeg/index/0/getactivestreams',
        success: function(val) {
            console.log(JSON.parse(val));

        }
    })
}

$(document).ready(function() {
    setInterval(function() {
        updateStatus();
        getActiveStreams();
    }, 2000);


});