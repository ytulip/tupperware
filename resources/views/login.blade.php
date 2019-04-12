@extends('_layout.master')
@section('title')
    <title>特百惠</title>
@stop
@section('style')
    <style>
        html{height: 100%;font-family: PingFangSC-Regular;}
        html,body{margin: 0;padding: 0;}
        input {
            caret-color: #E01885;
            outline: none;
            -webkit-appearance: none;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        }
        input:focus::-webkit-input-placeholder{
            color: transparent;
        }

        ::-webkit-input-placeholder { /* WebKit browsers */
            color:    #93989E;
        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color:    #93989E;
        }
        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color:    #93989E;
        }
        :-ms-input-placeholder { /* Internet Explorer 10+ */
            color:    #93989E;
        }
    </style>
@stop
@section('container')
    <header style="margin-top: 60px;line-height: 0;" class="t-al-c">
        <img src="/images/logo.png" style="width: 273px;"/>
    </header>

    <form style="padding: 0 24px;margin-top: 76px;" id="data_form">
        <div>
            <input placeholder="输入工号姓名" style="display:inline-block;line-height: 18px;font-size: 17px;color: #232A31;letter-spacing: 0;background: #F7F7F9;border: 1px solid #EEEEEE;border-radius: 100px;width: 100%;text-align: center;box-sizing: border-box;padding: 13px 0;" name="email"/>
        </div>


        <div style="margin-top: 20px">
            <input placeholder="输入密码" style="display:inline-block;line-height: 18px;font-size: 17px;color: #232A31;letter-spacing: 0;background: #F7F7F9;border: 1px solid #EEEEEE;border-radius: 100px;width: 100%;text-align: center;box-sizing: border-box;padding: 13px 0;" name="password" type="password"/>
        </div>

        <div style="margin-top: 32px;">
            <a class="t-al-c" style="display: inline-block;width: 100%;background: #E01885;box-shadow: 0 2px 8px 0 rgba(224,24,133,0.36);border-radius: 100px;line-height: 44px;font-size: 17px;color: #FFFFFF;letter-spacing: 0;" id="next_step">进入</a>
        </div>
    </form>
@stop

@section('script')
    <script>
        $(function () {
            new SubmitButton({
                selectorStr:"#next_step",
                url:'/passport/admin-login',
                data:function()
                {
                    return $('#data_form').serialize();
                },
                redirectTo:'/index/index'
            });
        });
    </script>
@stop