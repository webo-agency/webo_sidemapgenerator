{*{% extends fullscreen ? '@Modules/ps_metrics/views/templates/admin/fullscreen.html.twig' : '@PrestaShop/Admin/layout.html.twig' %}*}
{*{extends file=$layout}*}
{*{if !Module::isEnabled('gsitemap')}*}
{*    <div class="alert alert-danger" role="alert">*}
{*        <p class="alert-text">{l s='You must install and configurate Google Sidemap' d='Modules.WeboSideMapGenerator.Admin'} <a href="https://github.com/webo-agency/webo_sidemapgenerator" target="_blank"  class="alert-link">{l s='Read the documentation' d='Modules.WeboSideMapGenerator.Admin'}</a> </p>*}
{*    </div>*}
{*{else}*}
{*    {if !Configuration::get('WEBO_FILTERSITEMAP_TOKEN') || !Configuration::get('WEBO_FILTERSITEMAP_ACTIVE')}*}
{*        <div class="alert alert-danger" role="alert">*}
{*            <p class="alert-text fw-bold">{l s="You don't have generated token" d='Modules.WeboSideMapGenerator.Admin'}</p>*}
{*            <form action="" method="post"><div class="mt-2"><button type="submit" class="btn btn-primary" name="webo_generate_token">Generate new token</button></form></div>*}
{*        </div>*}
{*    {else}*}
{*        filter_vintage*}
{*        {block name="content"}*}
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <h3 class="card-header">
                        <i class="material-icons">business_center</i>
                    </h3>
                    <div class="card-block row">
                        <div class="card-text">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
{*        {/block}*}
{*    {/if}*}
{*{/if}*}
