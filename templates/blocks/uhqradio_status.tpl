<script type="text/javascript">
    <!--
    function uhq_popup(linkurl, popw, poph) {
        if (!window.focus) return true;
        var href;
        if (typeof(linkurl) === 'string')
            href = linkurl;
        else
            href = linkurl.href;
        window.open(href, 'uhq-radio', 'width=' + popw + ',height=' + poph + ',scrollbars=yes');
        return false;
    }
    function uhq_nocover(source) {
        source.src = "/modules/uhq_radio/images/nocover.png";
        // disable onerror to prevent endless loop
        source.onerror = "";
        return true;
    }
    //-->
</script>
<style type="text/css">
    .albumcover {
        width: 160px;
        height: auto;
    }
</style>
<{if $block.ajax == 1}>
<script type="text/javascript">
    <!--
    function uhqradioStatus() {
        // Initialize XML HTTP Object
        var xmlhttp = getXmlHttpObject();
        if (xmlhttp === null) {
            document.getElementByID("uhqradio_status").innerHTML = "AJAX Not Supported";
            return;
        }

        // Nested function which updates the actual fields.
        xmlhttp.onreadystatechange = function () {
            if ((xmlhttp.readyState == 4) && (xmlhttp.status == 200)) {
                var xmldoc = xmlhttp.responseXML.documentElement;

                // Locate block status.  We'll only display stuff if this status is 1.

                if (xmldoc.getElementsByTagName("status")) {
                    var blk_status = xmldoc.getElementsByTagName("status")[0].childNodes[0].nodeValue;
                } else {
                    var blk_status = null;
                }
                if (blk_status == 1) {
                    // Display On-Air Status
                    var blk_status_div = "<img src='<{$smarty.const._MB_UHQRADIO_ONAIR_IMG}>' alt='<{$smarty.const._MB_UHQRADIO_ONAIR}>'>";
                    document.getElementById("uhqradio_status").innerHTML = blk_status_div;

                    // Display Show Name, if present
                    if (xmldoc.getElementsByTagName("showname")[0]) {
                        var blk_statusdetail = xmldoc.getElementsByTagName("showname")[0].childNodes[0].nodeValue;
                        document.getElementById("uhqradio_statusdetail").innerHTML = blk_statusdetail;
                    } else {
                        document.getElementById("uhqradio_statusdetail").innerHTML = "";
                    }

                    //  Now Playing!

                    var blk_nowplaying = "<hr><b><{$smarty.const._MB_UHQRADIO_NOWPLAYING}></b> <br>";

                    if (xmldoc.getElementsByTagName("title")[0]) {
                        var blk_title = xmldoc.getElementsByTagName("title")[0].childNodes[0].nodeValue;
                    } else {
                        var blk_title = null;
                    }

                    if (blk_title !== null) {
                        if (xmldoc.getElementsByTagName("artist")[0]) {
                            var blk_artist = xmldoc.getElementsByTagName("artist")[0].childNodes[0].nodeValue;
                            if (blk_artist !== null) {
                                blk_nowplaying = blk_nowplaying + "<u>" + blk_artist + "</u><br>";
                            }
                        }
                        blk_nowplaying = blk_nowplaying + blk_title;
                        document.getElementById("uhqradio_nowplaying").innerHTML = blk_nowplaying;
                    } else {
                        document.getElementById("uhqradio_nowplaying").innerHTML = "";
                    }

                    // Load up album and album cover, if enabled.

                    <{if $block.showalbumart == 1}>
                    if (xmldoc.getElementsByTagName("albumcover")[0]) {
                        var blk_albumcover = xmldoc.getElementsByTagName("albumcover")[0].childNodes[0].nodeValue;
                    } else {
                        var blk_albumcover = null;
                    }
                    if (xmldoc.getElementsByTagName("album")[0]) {
                        var blk_album = xmldoc.getElementsByTagName("album")[0].childNodes[0].nodeValue;
                    } else {
                        var blk_album = null;
                    }
                    if ((blk_albumcover !== null)) {
                        var blk_album_div = '<img src="' + blk_albumcover + '" onerror="uhq_nocover(this)" class="albumcover" ';
                        if (blk_album != null) {
                            blk_album_div = blk_album_div + ' title="' + blk_album + '"';
                        }
                        blk_album_div = blk_album_div + '>';
                        document.getElementById("uhqradio_album").innerHTML = "<hr>" + blk_album_div;
                    } else {
                        document.getElementById("uhqradio_album").innerHTML = "";
                    }
                    <{/if}>

                    // Only show the listener count if it's enabled in the block.

                    <{if $block.showlisteners == 1}>
                    if (xmldoc.getElementsByTagName("listeners")[0]) {
                        var blk_listeners = xmldoc.getElementsByTagName("listeners")[0].childNodes[0].nodeValue;
                    } else {
                        var blk_listeners = null;
                    }

                    if (blk_listeners !== null) {
                        if (blk_listeners == 0) {
                            blk_listeners_div = "<{$smarty.const._MB_UHQRADIO_LISTENERS_NONE}>";
                        } else if (blk_listeners == 1) {
                            blk_listeners_div = "<{$smarty.const._MB_UHQRADIO_LISTENERS_ONE}>";
                        } else {
                            blk_listeners_div = "<{$smarty.const._MB_UHQRADIO_LISTENERS_MANY}>" + blk_listeners;
                        }
                        document.getElementById("uhqradio_listeners").innerHTML = "<hr>" + blk_listeners_div;
                    } else {
                        document.getElementById("uhqradio_listeners").innerHTML = "";
                    }
                    <{/if}>
                    // Only show the URL and link button if it's enabled in the block.

                    <{if $block.showurl}>
                    <{if $block.graphic}>
                    <{if $block.target == "pop"}>
                    var tune_div = '<a href="<{$block.linkurl}>" onClick="return uhq_popup(this, \'<{$block.popw}>\', \'<{$block.poph}>\')"><img src="<{$smarty.const._MB_UHQRADIO_TUNEIMG}>"></a>';
                    <{else}>
                    var tune_div = '<a href="<{$block.linkurl}>" target="<{$block.target}>"><img src="<{$smarty.const._MB_UHQRADIO_TUNEIMG}>"></a>';
                    <{/if}>
                    <{else}>
                    var tune_div = '<hr><b><{$smarty.const._MB_UHQRADIO_TUNEIN}></b>';
                    <{if $block.target == "pop"}>
                    tune_div = tune_div + '<a href="<{$block.linkurl}>" onClick="return uhq_popup(this, \'<{$block.popw}>\', \'<{$block.poph}>\')"><{$smarty.const._MB_UHQRADIO_TUNELINK}></a>';
                    <{else}>
                    tune_div = tune_div + '<a href="<{$block.linkurl}>" target="<{$block.target}>"><{$smarty.const._MB_UHQRADIO_TUNELINK}></a>';
                    <{/if}>
                    <{/if}>
                    document.getElementById("uhqradio_tunelink").innerHTML = tune_div;
                    <{else}>
                    document.getElementById("uhqradio_tunelink").innerHTML = "";
                    <{/if}>}
                else {
                    // Display off-air status
                    var blk_status_div = "<img src='<{$smarty.const._MB_UHQRADIO_OFFAIR_IMG}>' alt='<{$smarty.const._MB_UHQRADIO_OFFAIR}>'>";
                    document.getElementById("uhqradio_status").innerHTML = blk_status_div;

                    // Show errors, if enabled in the block
                    <{if $block.showerr}>
                    var blk_statusdetail = xmldoc.getElementsByTagName("error")[0].childNodes[0].nodeValue;
                    document.getElementById("uhqradio_statusdetail").innerHTML = blk_statusdetail;
                    <{else}>
                    document.getElementById("uhqradio_statusdetail").innerHTML = "";
                    <{/if}>

                    // Null out all other variables.
                    document.getElementById("uhqradio_nowplaying").innerHTML = "";
                    document.getElementById("uhqradio_album").innerHTML = "";
                    document.getElementById("uhqradio_listeners").innerHTML = "";
                    document.getElementById("uhqradio_tunelink").innerHTML = "";
                }
            }
        };

        // Load XML Data
        xmlhttp.open("GET", "/modules/uhq_radio/xml_status.php?chid=<{$block.chid}>", true);
        xmlhttp.send(null);
    }

    function getXmlHttpObject() {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            return new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            // code for IE6, IE5
            return new ActiveXObject("Microsoft.XMLHTTP");
        } else {
            return null;
        }
    }

    // Call the function and run it every 60 seconds.
    uhqradioStatus();
    setInterval(uhqradioStatus, 60000);
    -->
</script>
<div style="text-align: center;">
    <div id="uhqradio_status"></div>
    <div id="uhqradio_statusdetail">Loading ...</div>
    <div id="uhqradio_nowplaying"></div>
    <div id="uhqradio_album"></div>
    <div id="uhqradio_listeners"></div>
    <div id="uhqradio_tunelink"></div>
    <{else}>
    <div style="text-align: center;">
        <{if $block.statusimg}>
            <img src="<{$block.statusimg}>" alt="<{$block.status}>" title="<{$block.status}>">
        <{else}>
            <{$block.status}>
        <{/if}>
        <{if $block.statusdetail}><br><{$block.statusdetail}><{/if}>
        <{if $block.error}>
            <{if $block.errorcode}><{$block.errorcode}><{/if}>
        <{else}>
            <{if $block.title}>
                <hr>
                <b><{$smarty.const._MB_UHQRADIO_NOWPLAYING}></b>
                <br>
                <{if $block.artist}><u><{$block.artist}></u><br><{/if}>
                <{$block.title}>
            <{/if}>
            <{if $block.listeners}>
                <hr>
            <{/if}>
            <{if $block.listeners}><{$block.listeners}><br><{/if}>
            <{if $block.linkurl}>
                <{if $block.graphic}>
                    <{if $block.target == "pop"}>
                        <a href="<{$block.linkurl}>"
                           onClick="return uhq_popup(this, '<{$block.popw}>', '<{$block.poph}>')">
                            <img src="<{$smarty.const._MB_UHQRADIO_TUNEIMG}>"></a>
                    <{else}>
                        <a href="<{$block.linkurl}>" target="<{$block.target}>">
                            <img src="<{$smarty.const._MB_UHQRADIO_TUNEIMG}>"></a>
                    <{/if}>
                <{else}>
                    <hr>
                    <b><{$smarty.const._MB_UHQRADIO_TUNEIN}></b>
                    <{if $block.target == "pop"}>
                        <a href="<{$block.linkurl}>"
                           onClick="return uhq_popup(this, '<{$block.popw}>', '<{$block.poph}>')">
                            <{$smarty.const._MB_UHQRADIO_TUNELINK}></a>
                    <{else}>
                        <a href="<{$block.linkurl}>" target="<{$block.target}>">
                            <{$smarty.const._MB_UHQRADIO_TUNELINK}></a>
                    <{/if}>
                <{/if}>
            <{/if}>
        <{/if}>
        <{/if}>
    </div>
