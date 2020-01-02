<style>
    <!--
    .djlist {

    font-size:< {
        $ block . size;
    }

    >
    ;
    }
    -->
</style>
<{if $block.djcount > 0}>
    <table class="djlist">
        <{foreach item=list from=$block.djs key=key}>
            <{if ($key mod $block.cols) == 0}>
                <tr><{/if}>
            <td><a href="/modules/uhq_radio/djprofile.php?djid=<{$list.djid}>"><{$list.name}></a></td>
            <{if ($key mod $block.cols) == 1}>
                </tr>
            <{/if}>
        <{/foreach}>
    </table>
<{/if}>
