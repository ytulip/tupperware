@extends('admin.master',['headerTitle'=>''])
@section('left_content')
    <div class="mt-32 padding-col">



        <div class="row">
            <div class="col-md-4 col-lg-4" onclick="goHref('/admin/index/users?get_status=1')">
                <div class="block-card border-style1">
                    <p class="t-al-c fs-32-fc-232A31">{{$total}}</p>
                    <div class="t-al-c fs-14-fc-93989e"><span style="background: #E01885;box-shadow: 0 2px 4px 0 rgba(224,24,133,0.40);width: 8px;height: 8px;display: inline-block;border-radius: 8px;margin-right: 8px;vertical-align: middle;"></span><span style="display: inline-block;vertical-align: middle;">用户人数</span></div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4" onclick="goHref('/admin/index/users?get_status=2')">
                <div class="block-card border-style1">
                    <p class="t-al-c fs-32-fc-232A31">{{$hasUpload}}</p>
                    <div class="t-al-c fs-14-fc-93989e"><span style="background: #DB5DF1 ;box-shadow: 0 2px 4px 0 rgba(224,24,133,0.40);width: 8px;height: 8px;display: inline-block;border-radius: 8px;margin-right: 8px;vertical-align: middle;"></span><span style="display: inline-block;vertical-align: middle;">上传人数</span></div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4" onclick="goHref('/admin/index/users?get_status=3')">
                <div class="block-card border-style1">
                    <p class="t-al-c fs-32-fc-232A31">{{intval(($hasUpload/$total) * 100)}}%</p>
                    <div class="t-al-c fs-14-fc-93989e"><span style="background: #686CFA;box-shadow: 0 2px 4px 0 rgba(224,24,133,0.40);width: 8px;height: 8px;display: inline-block;border-radius: 8px;margin-right: 8px;vertical-align: middle;"></span><span style="display: inline-block;vertical-align: middle;">上传比例</span></div>
                </div>
            </div>
        </div>

        {{--<div class="row m-t-20">--}}
            {{--<div class="col-md-12 col-lg-12">--}}
                {{--<form class="form-inline" id="search_form">--}}

                    {{--<div class="form-group">--}}
                        {{--<label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;--}}
    {{--padding: 0;--}}
    {{--text-align: left;">开始时间</label>--}}
                        {{--<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">--}}
                            {{--<input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('start_time')}}" name="start_time" >--}}
                            {{--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>--}}
                        {{--</div>--}}
                        {{--<input type="hidden" id="dtp_input2" value="" /><br/>--}}
                    {{--</div>--}}


                    {{--<div class="form-group">--}}
                        {{--<label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;--}}
    {{--padding: 0;--}}
    {{--text-align: left;">结束时间</label>--}}
                        {{--<div class="input-group date form_date col-md-5" data-date="" data-date-format="yyyy-mm-dd" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">--}}
                            {{--<input class="form-control" size="16" type="text" value="{{\Illuminate\Support\Facades\Request::input('end_time')}}" name="end_time" >--}}
                            {{--<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>--}}
                        {{--</div>--}}
                        {{--<input type="hidden" id="dtp_input2" value="" /><br/>--}}
                    {{--</div>--}}


                    {{--<div class="form-group v-a-b">--}}
                        {{--<label for="dtp_input2" class="col-md-2 control-label" style="width: 120px;--}}
    {{--padding: 0;--}}
    {{--text-align: left;">发货状态</label>--}}
                        {{--<div class="input-group">--}}
                            {{--<select type="text" class="form-control" id="exampleInputAmount" name="get_status">--}}
                                {{--<option></option>--}}
                                {{--<option value="1" {{\Illuminate\Support\Facades\Request::input('get_status') == "1"?' selected':''}}>待发货</option>--}}
                                {{--<option value="2" {{\Illuminate\Support\Facades\Request::input('get_status') == "2"?' selected':''}}>已发货</option>--}}
                                {{--<option value="3" {{\Illuminate\Support\Facades\Request::input('get_status') == "3"?' selected':''}}>已自提</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}


                    {{--<div class="form-group v-a-b">--}}
                        {{--<div class="input-group">--}}
                            {{--<input type="text" class="form-control" id="exampleInputAmount" placeholder="输入姓名、手机号搜索" name="keyword" value="{{\Illuminate\Support\Facades\Request::input('keyword')}}">--}}
                            {{--<div class="input-group-addon"><i class="fa fa-search" onclick="search()"></i></div>--}}
                        {{--</div>--}}
                    {{--</div>--}}


                    {{--<div class="form-group v-a-b">--}}
                        {{--<div class="input-group">--}}
                            {{--<a class="btn btn-info" href="javascript:commonDownload()">下载</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="col-md-2 col-lg-2">--}}
            {{--<a class="btn btn-info" href="javascript:commonDownload()" style="margin-top: 18px;">下载</a>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="tr-border fs-14-fc-4E5761 fn-fa" style="margin-top: 24px;">
            <div class="row" style="font-size: 0;text-align: right;">
                <div style="float:left;margin-left: 15px;line-height: 34px;" class="fs-16-fc-232A31">用户列表</div>
                <div style="position: relative;display: inline-block;">
                    <input style="background: #FCFCFC;border: 1px solid #EAEEF7;border-radius: 100px;padding: 8px 12px;" class="fs-14-fc-93989e fn-fa" placeholder="输入ID搜索" name="work_no" value="{{\Illuminate\Support\Facades\Request::input('work_no')}}"/>
                    <a style="position: absolute;right: 10px;top:9px;" class="search_btn"><img src="/images/icon_search_nor@3x.png" width="14px"/></a>
                </div>

                <a class="btn-new search_btn_download" style="margin-left: 16px;">导出数据</a>
            </div>
        </div>


        <div class="tr-border fs-14-fc-4E5761 fn-fa bg-fc" style="margin-top: -1px;">
            <div class="row">
                <div class="col-md-2 col-lg-2">用户ID</div>
                <div class="col-md-2 col-lg-2"><select class="selectpicker selectpicker1" title="所属省份">
                        <option @if( \Illuminate\Support\Facades\Request::input('province') == '全部' ) selected @endif>全部</option>
                        @foreach($provinceList as $item)
                            <option @if( \Illuminate\Support\Facades\Request::input('province') == $item->province ) selected @endif>{{$item->province}}</option>
                            @endforeach
                        {{--<option>Mustard</option>--}}
                        {{--<option>Ketchup</option>--}}
                        {{--<option>Relish</option>--}}
                    </select>
                </div>
                <div class="col-md-2 col-lg-2"><select class="selectpicker selectpicker2" title="是/否上传">
                        <option @if( \Illuminate\Support\Facades\Request::input('is_upload') == '全部' ) selected @endif>全部</option>
                        <option @if( \Illuminate\Support\Facades\Request::input('is_upload') == '已上传' ) selected @endif>已上传</option>
                        <option @if( \Illuminate\Support\Facades\Request::input('is_upload') == '未上传' ) selected @endif>未上传</option>
                    </select></div>
                <div class="col-md-4 col-lg-4"></div>
                <div class="col-md-2 col-lg-2">上传次数</div>
            </div>
        </div>

        {{--<div class="block-card">--}}
        @foreach($paginate as $item)
            <div class="tr-border fs-14-fc-4E5761 fn-fa record-item" style="margin-top: -1px;" onclick="goDetail({{$item->id}})"><div class="row">
                <div class="col-md-2 col-lg-2">{{$item->work_no}}</div>
                <div class="col-md-2 col-lg-2">{{$item->province}}</div>
                <div class="col-md-2 col-lg-2">{{$item->upload_count?'是':'否'}}</div>
                <div class="col-md-4 col-lg-4"></div>
                <div class="col-md-2 col-lg-2">{{intval($item->upload_count)}}</div>
                </div></div>
        @endforeach

        @if($paginate->perPage() > count($paginate) )
            @for($i =0;$paginate->perPage() > (count($paginate) + $i);$i++)
                <div class="tr-border fs-14-fc-4E5761 fn-fa record-item" style="border-bottom: 0;border-top: 0;"><div class="row">
                        <div class="col-md-2 col-lg-2">&nbsp;</div>
                        <div class="col-md-2 col-lg-2"></div>
                        <div class="col-md-2 col-lg-2"></div>
                        <div class="col-md-4 col-lg-4"></div>
                        <div class="col-md-2 col-lg-2"></div>
                    </div></div>
                @endfor
                <div style="border-top: 1px solid #EAEEF7"></div>
            @endif
        {{--</div>--}}

        {{--<div class="block-card">--}}
        @if($paginate->lastPage() > 1)
            <div class="tr-border" style="overflow: hidden;margin-top: -1px;">
                <div class="fl-r"><?php echo $paginate->appends(\Illuminate\Support\Facades\Request::all())->render(); ?></div>
            </div>
        @endif
        {{--</div>--}}

    </div>
@stop

@section('script')
    <script>
        function goDetail(id)
        {
            location.href = '/admin/index/user-detail?id=' + id;
        }

        function search()
        {
//    alert(1);
//    $val = $('#search_user').val();
            $('#search_form').submit();

        }

        $('.selectpicker').on('changed.bs.select',function(e){
            // console.log(e);
            // console.log($('.selectpicker1').val());
            location.href = '/admin/index/home?province=' + $('.selectpicker1').val() +'&is_upload=' + $('.selectpicker2').val() + '&work_no=' + $('input[name="work_no"]').val();
        });

        $('.search_btn').click(function(){
            location.href = '/admin/index/home?province=' + $('.selectpicker1').val() +'&is_upload=' + $('.selectpicker2').val() + '&work_no=' + $('input[name="work_no"]').val();
        });

        $('.search_btn_download').click(function(){
            location.href = '/admin/index/home?download=1&province=' + $('.selectpicker1').val() +'&is_upload=' + $('.selectpicker2').val() + '&work_no=' + $('input[name="work_no"]').val();
        });
    </script>
@stop