<p><b><{$smarty.const._AM_UHQRADIO_INDEX}></b></p>
<ul>
    <li>
        <{$data.summary.1.count}>
        <{if $data.summary.1.count == 1}>
            <{$smarty.const._AM_UHQRADIO_AIR_ONE}>
        <{else}>
            <{$smarty.const._AM_UHQRADIO_AIR_PLU}>
        <{/if}>
    </li>

    <li>
        <{$data.summary.2.count}>
        <{if $data.summary.2.count == 1}>
            <{$smarty.const._AM_UHQRADIO_MOUNT_ONE}>
        <{else}>
            <{$smarty.const._AM_UHQRADIO_MOUNT_PLU}>
        <{/if}>
    </li>

    <li>
        <{$data.summary.3.count}>
        <{if $data.summary.3.count == 1}>
            <{$smarty.const._AM_UHQRADIO_CHANNEL_ONE}>
        <{else}>
            <{$smarty.const._AM_UHQRADIO_CHANNEL_PLU}>
        <{/if}>
    </li>
</ul>
<br>

<p><b><{$smarty.const._AM_UHQRADIO_TEST_HEADER}></b></p>
<ul>
    <{foreach item=list from=$data.test}>
        <li>
            <{$list.test}> -
            <{if $list.result}>
            <span color=green><{$smarty.const._AM_UHQRADIO_TEST_OK}>
                <{else}>
                <span style="color:red;"><{$smarty.const._AM_UHQRADIO_TEST_NOK}>
                    <{/if}>
                </span>
        </li>
    <{/foreach}>
</ul>
<br>

<p><b><{$smarty.const._AM_UHQRADIO_INDEX_MODULES}></b></p>
<ul>
    <{foreach item=list from=$data.modules}>
        <li><{$list.module}> -
            <{if $list.installed}>
                <span style="color: green; "><{$smarty.const._AM_UHQRADIO_MODULE_INSTALLED}></span>
            <{else}>
                <{$smarty.const._AM_UHQRADIO_MODULE_NA}>
            <{/if}>
        </li>
    <{/foreach}>
</ul>
