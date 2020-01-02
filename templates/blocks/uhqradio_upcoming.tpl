<{if $block.error}>
    <{$block.error}>
<{else}>
    <{$block.options.4}>
    <{foreach item=list from=$block.upcoming key=key}>
        <{if $key >0}> <{$block.options.5}><{/if}><{$list.artist}><{if $list.requestid}>
        <class
        ="uhqradio-req"> [Requested] </class><{/if}>
    <{/foreach}>
    <{$block.options.6}>
<{/if}>
