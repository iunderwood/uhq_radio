<script type="text/javascript">
    <!--
    function songinfo(songid) {
        window.open("pop_songinfo.php?songid=" + songid, "songinfowin", "location=no,status=no,menubar=no,scrollbars=yes,resizeable=yes,height=300,width=650");
    }

    function request(songid) {
        window.open("pop_request.php?songid=" + songid, "songinfowin", "location=no,status=no,menubar=no,scrollbars=yes,resizeable=yes,height=300,width=650");
    }
    -->
</script>

<{if $data.samint == 0}>
    <{$smarty.const._MD_UHQRADIO_PLAYLIST_NA}>
<{else}>
    <!--  Header Table -->
    <table>
        <tr>
            <td align=left valign=middle>
                <form method=POST action="sm-tracklist.php">
                    <{if $data.rawsearch}>
                        <input type="submit" value="<{$smarty.const._MD_UHQRADIO_PLAYLIST_CLEARSEARCH}>">
                    <{/if}>
                    <{if $data.trackcount == 0}>
                        <{$smarty.const._MD_UHQRADIO_PLAYLIST_NOTRACKS}>
                    <{elseif $data.trackcount == 1}>
                        <{$smarty.const._MD_UHQRADIO_PLAYLIST_ONETRACK}>
                    <{else}>
                        <{$data.trackcount}><{$smarty.const._MD_UHQRADIO_PLAYLIST_MORETRACKS}>
                    <{/if}>
                </form>
            </td>
            <{if $data.trackcount > 0}>
                <td align=right valign=middle>
                    <{if $data.start > 0}>
                    <{if $data.start - $data.limit < 0}>
                    &lt;&lt; <a href="sm-tracklist.php">
                        <{else}>
                        &lt;&lt; <a
                                href="sm-tracklist.php?pl_start=<{$data.start-$data.limit}><{if $data.urlsearch}>&pl_search=<{$data.urlsearch}><{/if}>">
                            <{/if}>
                            <{$smarty.const._MD_UHQRADIO_PLAYLIST_PREV}></a> ::
                        <{/if}>
                        <{$smarty.const._MD_UHQRADIO_PLAYLIST_SHOW}> <{$data.start+1}> <{$smarty.const._MD_UHQRADIO_PLAYLIST_THRU}>
                        <{if $data.start + $data.limit < $data.trackcount}>
                            <{$data.start+$data.limit}> ::
                            <a href="sm-tracklist.php?pl_start=<{$data.start+$data.limit}><{if $data.urlsearch}>&pl_search=<{$data.urlsearch}><{/if}>"><{$smarty.const._MD_UHQRADIO_PLAYLIST_NEXT}></a>
                            &gt;&gt;
                        <{else}>
                            <{$data.trackcount}>
                        <{/if}>
                </td>
            <{/if}>
        </tr>
    </table>
    <!-- Playlist table, only if we have a track count. -->
    <{if $data.trackcount > 0}>
        <hr>
        <table>
            <tr>
                <th width=1%>#</th>
                <th>Artist</th>
                <th>Title</th>
                <th>Album</th>
                <th width=1%>Time</th>
                <th width=1%>Links</th>
            </tr>
            <{foreach item=list from=$data.tracklist}>
                <tr class="<{cycle values=" odd,odd,even,even"}>">
                    <td><{$list.seq}></td>
                    <td><{$list.artist}></td>
                    <td><{$list.title}></td>
                    <td><{$list.album}></td>
                    <td><{$list.mmss}></td>
                    <td nowrap>
                        <a href="javascript:songinfo(<{$list.ID}>)"><{$smarty.const._MD_UHQRADIO_PLAYLIST_LINK_INFO}></a>
                        <{if $data.showreq}>
                            ::
                            <a href="javascript:request(<{$list.ID}>)"><{$smarty.const._MD_UHQRADIO_PLAYLIST_LINK_REQ}></a>
                        <{/if}>
                    </td>
                </tr>
            <{/foreach}>
        </table>
    <{/if}>
    <!-- Search & Adjust List Length -->
    <hr>
    <table>
        <tr>
            <td align=left>
                <form method=POST action="sm-tracklist.php">
                    Search:
                    <input type="text" name="pl_search" size="30"
                           <{if $data.rawsearch}>value="<{$data.rawsearch}>"<{/if}>>
                    <input type="submit" value="Go">
                </form>
            </td>
            <td align=right>
                <form method=POST action="sm-tracklist.php">
                    <{securityToken}><{*//mb*}>
                    <select name="pl_limit" size="1" onchange="this.form.submit();">
                        <{foreach item=list from=$data.increments}>
                            <option value="<{$list}>"
                                    <{if $list == $data.limit}>selected<{/if}>><{$list}> per page
                            </option>
                        <{/foreach}>
                    </select>
                    <input type="hidden" name="pl_start" value="<{$data.start}>">
                    <{if $data.rawsearch}><input type="hidden" name="pl_search" value="<{$data.rawsearch}>"><{/if}>
                </form>
            </td>
        </tr>
    </table>
<{/if}>
