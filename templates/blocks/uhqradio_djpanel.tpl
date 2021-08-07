<script type="text/javascript">
    <!--
    function uhq_djtest(djid, host) {
        window.open("/modules/uhqradio/pop_djtest.php?djid=" + djid + "&host=" + host, "djtest", "location=no,status=no,menubar=no,scrollbars=yes,resizeable=no,height=150,width=400");

        return true;
    }
    -->
</script>

<{$smarty.const._MB_UHQRADIO_DJP_ID}> <b><a
            href="/modules/uhqradio/djprofile.php?djid=<{$block.djid}>"><{$block.djid}></a></b>
<hr>
<{if $block.samint == 1}>
    <table>
        <tr>
            <th colspan=2><{$smarty.const._MB_UHQRADIO_DJP_SAM}></th>
        </tr>
        <tr>
            <td><{$smarty.const._MB_UHQRADIO_DJP_SAM_PORT}></td>
            <td><{$block.sam_port}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MB_UHQRADIO_DJP_RDB_PORT}></td>
            <td><{$block.rdb_port}></td>
        </tr>
        <tr>
            <td><{$smarty.const._MB_UHQRADIO_DJP_RDB_NAME}></td>
            <td><{$block.rdb_name}></td>
        </tr>
    </table>
    <hr>
    <a href="javascript:uhq_djtest('<{$block.djid}>','<{$block.reqip}>')">Test Connectivity</a>
    <hr>
<{/if}>
<{$block.reqstat}>
