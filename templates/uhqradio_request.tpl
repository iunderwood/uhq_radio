<script type="text/javascript">
    <!--
    function songinfo(songid) {
        window.open("pop_songinfo.php?songid=" + songid, "songinfowin", "location=no,status=no,menubar=no,scrollbars=yes,resizeable=yes,height=300,width=650");
    }

    function uhq_nocover(source) {
        source.src = "/modules/uhqradio/images/nocover.png";
        // disable onerror to prevent endless loop
        source.onerror = "";
        return true;
    }
    -->
</script>
New Request Engine Here.
<hr>
<a href="?op=b_album">Browse by Album</a> - <a href="?op=b_artist">Browse by Artist</a>
<hr>
<table>
    <tr>
        <td><{$data.totalcount}>
            <{if $data.op == "b_album"}>albums
            <{elseif $data.op == "s_album"}>albums
            <{else}>artists
            <{/if}>
            in the database.
        </td>
        <td align=right>:
            <{foreach item=list from=$data.az}>
                <a href="request.php?<{$list.link}>"><{$list.letter}></a>
                .
            <{/foreach}>
        </td>
</table>
<hr>

<{if (($data.op == "b_album") || ($data.op == "b_artist")) }>

    <{if $data.start != null}>
        <p><{$data.itemcount}> item<{if $data.start != 1}>s<{/if}> starting
            with <{if $data.start}><{$data.start}><{else}>numbers<{/if}></p>
        <{if $data.itemcount > 0}>
            <{foreach item=list from=$data.itemlist}>
                <{if $data.op == "b_album"}>
                    <div style="width:170px; height:190px; border-style:solid; border-width:1px; padding:5px; margin:5px; float:left; text-align:center;">
                        <a href="request.php?op=s_album&info=<{$list.linkcode}>">
                            <img src="<{$data.baseurl}>/<{$data.djid}>/<{$list.cover}>"
                                 style="max-width:160px; max-height:160px;"><br>
                            <{$list.album}></a>
                    </div>
                <{/if}>
                <{if $data.op == "b_artist"}><a href="request.php?op=s_artist&info=<{$list.linkcode}>"
                                                align=left><{$list.artist}></a>
                    <br>
                <{/if}>
            <{/foreach}>
        <{/if}>
    <{else}>
        <h3>Last <{$data.albumlimit}> Additions</h3>
        <{foreach item=list from=$data.newalbums}>
            <p><a href="request.php?op=s_album&info=<{$list.linkcode}>"><img
                            src="<{$data.baseurl}>/<{$data.djid}>/<{$list.cover}>" width=40 height=40 align=left
                            padding=2px></a><i><{$list.album}></i><br><{$list.date_add}></p>
        <{/foreach}>
    <{/if}>
<{elseif $data.op == 's_album'}>
    <table>
        <tr>
            <td width=170px>
                <img src="<{$data.baseurl}>/<{$data.djid}>/<{$data.album.picture}>" onerror="uhq_nocover(this)">
            </td>
            <td width=400px align=left>
                <h3><{$data.album.name}></h3>

                <p><{$data.album.artist}></p>

                <p>Genre: <{$data.album.genre}></p>

                <p><{if $data.album.label}>Label/Year: <{$data.album.label}> - <{else}>Year: <{/if}><{$data.album.year}></p>

                <p>Date Added: <{$data.album.added}></p>
            </td>
        </tr>
    </table>
    <table width=400px>
        <tr>
            <th width=1%>#</th>
            <th>Artist</th>
            <th>Title</th>
            <th>Duration</th>
            <th>Info</th>
        </tr>
        <{foreach item=list from=$data.track}>
            <tr class="<{cycle values=" odd,odd,even,even"}>">
                <td><{if $list.trackno}><{$list.trackno}><{else}>-<{/if}></td>
                <td><{$list.artist}></td>
                <td><{$list.title}></td>
                <td><{$list.mmss}></td>
                <td><a href="javascript:songinfo(<{$list.ID}>)">Info</a></td>
            </tr>
        <{/foreach}>
    </table>
<{elseif $data.op == "s_artist"}>
    <{$data.trackcount}> tracks by
    <i><{$data.artist}></i>
    <hr>
    <{foreach item=album from=$data.album}>
        <div style="border-style:solid; border-width:1px; padding:5px; margin:5px; overflow:hidden;">
            <div style="width:170px; float:left; text-align:center;">
                <img src="<{$data.baseurl}>/<{$data.djid}>/<{$album.picture}>" style="max-width:160px;"
                     onerror="uhq_nocover(this)"><br>

                <p><b><{$album.name}></b> (<{$album.year}>)</p>

                <p><{$album.label}></p>

                <p><{$album.genre}></p>
            </div>
            <div style="margin-left:180px;">
                <table>
                    <tr>
                        <th style="width:20px;">#</th>
                        <th>Title</th>
                        <th style="width:40px;">Link</th>
                        <th style="width:40px;">Time</th>
                    </tr>
                    <{foreach item=track from=$album.track}>
                        <tr class="<{cycle values=" odd,odd,even,even"}>">
                            <td><{if $track.trackno}><{$track.trackno}><{else}>-<{/if}></td>
                            <td><{$track.title}></td>
                            <td><a href="javascript:songinfo(<{$track.ID}>)">Info</a></td>
                            <td><{$track.mmss}></td>
                        </tr>
                    <{/foreach}>
                </table>
            </div>
        </div>
    <{/foreach}>
<{/if}>
