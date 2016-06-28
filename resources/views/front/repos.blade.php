@extends('layouts.front')

@section('contents')
    <div class="container">
        <div class="row" style="margin: 50px 0 50px 0">
            <div class="col-md-5 col-sm-5 hidden-xs">
                <img src="http://awesomes.img-cn-beijing.aliyuncs.com/repo/151114023816-81.jpg@1e_300w_207h_1c_0i_1o_1x.png" class="cover">
            </div>
            <div class="col-md-7 col-sm-7">
                <div class="repo-title">
                    <h1>
                        Magnific-popup
                    </h1>
                    <p>
                        Light and responsive lightbox script with focus on performance.
                    </p>
                </div>
                <div class="menu hidden-xs">
                    <a target="_blank" href="http://dimsemenov.com/plugins/magnific-popup/"><i class="fa fa-home"></i> 官 网 </a>
                    </a><a target="_blank" href="https://github.com/dimsemenov/Magnific-Popup" class="gitbtn"><i class="fa fa-github"></i> Github  </a>
                </div>
                <div class="params hidden-xs">
                    <div style="border-left: 0" title="星标数量">
                        <i class="fa fa-star"></i> <span>8211</span>
                    </div>
                    <div title="最后更新时间">
                        <i class="fa fa-clock-o"></i> <span>3周前</span>
                    </div>
                    <div title="Fork数量">
                        <i class="fa fa-code-fork"></i> <span>1903</span>
                    </div>
                    <div title="issue 响应速度">
                        <i class="fa fa-exclamation-circle"></i> <span>2周</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Non-responsive Bootstrap</h1>
                    <p class="lead">Disable the responsiveness of Bootstrap by fixing the width of the container and using the first grid system tier. <a href="http://getbootstrap.com/getting-started/#disable-responsive">Read the documentation</a> for more information.</p>
                </div>

                <h3>What changes</h3>
                <p>Note the lack of the <code>&lt;meta name="viewport" content="width=device-width, initial-scale=1"&gt;</code>, which disables the zooming aspect of sites in mobile devices. In addition, we reset our container's width and changed the navbar to prevent collapsing, and are basically good to go.</p>

                <h3>Regarding navbars</h3>
                <p>As a heads up, the navbar component is rather tricky here in that the styles for displaying it are rather specific and detailed. Overrides to ensure desktop styles display are not as performant or sleek as one would like. Just be aware there may be potential gotchas as you build on top of this example when using the navbar.</p>

                <h3>Browsers, scrolling, and fixed elements</h3>
                <p>Non-responsive layouts highlight a key drawback to fixed elements. <strong class="text-danger">Any fixed component, such as a fixed navbar, will not be scrollable when the viewport becomes narrower than the page content.</strong> In other words, given the non-responsive container width of 970px and a viewport of 800px, you'll potentially hide 170px of content.</p>
                <p>There is no way around this as it's default browser behavior. The only solution is a responsive layout or using a non-fixed element.</p>

                <h3>Non-responsive grid system</h3>
            </div>
        </div>
    </div>

@endsection