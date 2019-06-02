import UrlParse from 'url-parse'


function queryValue(name)
{
    let urlParse = UrlParse(location.href,true);
    let query = urlParse.query;
    return query[name]?query[name]:'';
}


function cusHttp(page,url,param,method)
{
    page.$http.interceptors.request.use((request)=>{
        request.headers["Authorization"]="JWT " + getAuth()
    })

    if( method == 'get')
    {
        return page.$http.get( dynamicHost() + url,param);
    }else
    {
        return page.$http.post(dynamicHost() + url,param);
    }
}


function dynamicHost()
{
    return 'http://tp.cc';
}


export default {
    dynamicHost:dynamicHost
}