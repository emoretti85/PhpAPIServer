<html>
<head>
<title>Example Use PhpApiServer</title>
</head>
<body>
<h1>Example of use of PhpApiServer</h1>


<h2>General Description.</h2>
<img src="img/flag-it.gif" width="32" height="21"/>
<p><i>
"Avevo bisogno di un server veloce, basilare ed efficace per un'app android che sto realizzando,<br/>
Così ho pensato di creare una base da utilizzare anche per future evenienze.<br/>
Ed è nato PhpApiServer, nome discutibile lo so!, ma per ora basta e avanza al mio scopo.<br/>
Magari in futuro potrei ampliarlo con nuove features...come sempre accetto critiche e\o suggerimenti da<br/> 
parte vostra!."</i><br/><b>E.Moretti</b><br/>
</p>
<br/>
<img src="img/icon_flag_us_en.gif" width="32" height="21"/>
<p>
<i>"I needed a fast, basic and effective server app for Android that I'm making, <br/> 
So I decided to create a base to use for future eventualities. <br/> 
And was born PhpApiServer, name questionable know! but for now is more than enough for my purpose. <br/> 
Maybe in the future I might extend it with new features ... as always accept criticism and \ or suggestions <br/> 
your part!."</i> 
<br /> <b> E.Moretti </b> <br/>
</p>


<h2>Communication with the server</h2>
<img src="img/flag-it.gif" width="32" height="21"/>
<p>
La comunicazione con il server avviene attraverso il protocollo HTTP.<br/><br/>
La richiesta dovrà essere simile alla seguente:
<b>http://&lt;indirizzo del vostro server&gt;/&lt;NOME API&gt;/&lt;NOME METODO&gt;?arg1=value1&arg2=value2&...argN=valueN</b>
<br/>Esempio: <b>http://localhost/User/login?username=myname&password=mypassword</b><br/><br/>

In caso di errori di invocazione, formattazione e quant'altro il server invierà una response in formato JSON<br/>
Per quanto riguarda le vostre API, siete liberi di utilizzare altri formati, se lo ritenete opportuno.<br/>
</b>
</p>

<br/>
<img src="img/icon_flag_us_en.gif" width="32" height="21"/>
<P> 
The communication with the server is via HTTP. <Br/ > <br/> 
The request should be similar to the following: <b>http://<your server address>/<API NAME>/<METHOD NAME>?arg1=value1&arg2=value2&...argN=valueN</b> <br/ >
Example: <b>http://localhost/User/login?Username=myname&Password=mypassword</b> <br/> <br/> 
In case of errors of invocation, formatting, and whatever else the server will send a response in JSON format<br/> 
As regards your API, you are free to use other formats, if appropriate. <br/> </b>


<h2>How to build your own API</h2>
<img src="img/flag-it.gif" width="32" height="21"/>
<p>
1-Creare una classe con il nome della vostra API.<br/>
2-Implementare l'interfaccia API.<br/>
3-Aggiungete un costruttore (anche vuoto)<br/>
<pre>
        public function __construct(){}
</pre><br/>
4-Aggiungete il metodo getDesc():<br>
<pre>
	public static function getDesc(){
		return "User class, used for login and register a user";
	}
</pre>
<br/>
5-Aggiungete la vostra fantasia in ogni metodo! :)

<br/><br/>
<img src="img/icon_flag_us_en.gif" width="32" height="21"/>

</p>
1-create aclass with the name of your API.<br/> 
2-Implement interface API. <br/>
3-Add a constructor (also empty) 
<pre> public function __construct() {}</pre> 

<br/>4-Add the getDesc method (): 
<pre>
 static function getDesc () {
    return ' User class, used for login and register a user;
 } 
</pre>
<br/>5-Add your fantasy in every method! :)

<h2>Describe server tool</h2>
<img src="img/flag-it.gif" width="32" height="21"/>

<p>
Describe è una API di sistema, che permette di recuperare informazioni utili sulla API di nostro interesse.
<br/>
Esempio:
<b>http://localhost/Describe/&lt;NOME API&gt;</b>
<br/>
Json Response:<br/>
<pre>
{
    Api name: "User",
    Callable Method: {
        1: {
            Name: "login",
            Parameter: ["username","password"]
           },
        2: {
            Name: "register"
            }
    },
    
    Short description: "User class, used for login and register a user"
}
</pre>

<br/>
<img src="img/icon_flag_us_en.gif" width="32" height="21"/>
<p>
Describe is a system API, which allows you to retrieve information about our interest in API.<br/>
Example:<b> http://localhost/Describe/&lt;API NAME&gt;</b>
<br/>Json Response:
<pre>
{
    Api name: "User",
    Callable Method: {
        1: {
            Name: "login",
            Parameter: ["username","password"]
           },
        2: {
            Name: "register"
            }
    },
    
    Short description: "User class, used for login and register a user"
}
</pre>

</p>


</p>

<h2>Android Example use</h2>
<img src="img/flag-it.gif" width="32" height="21"/>
<p>Magari per chi come me utilizzerà questo codice per una app mobile android potrebbe essere utile capire come invocare una API
<br/><br/><img src="img/icon_flag_us_en.gif" width="32" height="21"/>
<br/><br/>Maybe for those who like me will use this code to an android mobile app you might want to figure out how to invoke an API

<br/><br/>
<b>EXAMPLE ANDROID JAVA CODE</b>
<br/>
[...]<br/>
<pre>
public static JSONObject getJSONFromHttpPost(String URL) {

    try{
    // Create a new HttpClient and Post Header
    DefaultHttpClient httpclient = new DefaultHttpClient();
    HttpPost httpPost = new HttpPost(URL);
    String resultString = null;




        long t = System.currentTimeMillis();
        HttpResponse response = (HttpResponse) httpclient.execute(httpPost);
        System.out.println("HTTPResponse received in [" + (System.currentTimeMillis()-t) + "ms]");
        // Get hold of the response entity (-> the data):
        HttpEntity entity = response.getEntity();

        if (entity != null) {
            // Read the content stream
            InputStream instream = entity.getContent();

            // convert content stream to a String
            resultString= convertStreamToString(instream);
            instream.close();
            System.out.println("result String : " + resultString);
            //resultString = resultString.substring(1,resultString.length()-1); // remove wrapping "[" and "]"
            System.out.println("result String : " + resultString);
            // Transform the String into a JSONObject
            JSONObject jsonObjRecv = new JSONObject(resultString);
            // Raw DEBUG output of our received JSON object:
            System.out.println("<JSONObject>\n"+jsonObjRecv.toString()+"\n</JSONObject>");


            return jsonObjRecv;
        }
        }catch(Exception e){e.printStackTrace();}


        return null;
 }
 
 private static String convertStreamToString(InputStream is) {

    BufferedReader reader = new BufferedReader(new InputStreamReader(is));
    StringBuilder sb = new StringBuilder();

    String line ="";
    try {
        while ((line = reader.readLine()) != null) {
            sb.append(line + "\n");
        }
    } catch (IOException e) {
        e.printStackTrace();
    } finally {
        try {
            is.close();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
    return sb.toString();
}
</pre>
[...]</br>

<b>JSON String</b>
<pre>
{
"result": "0",
"LoginResponse":
[
  {"FirstName":"Ettore",
   "LastName":"Moretti"
   "BirtDate":"01/01/1901",
   "CountryName":"Italy"
  }
] 
}
</pre>
</p>
<br/><br/><br/>






</body>
</html>
<?php exit; ?>