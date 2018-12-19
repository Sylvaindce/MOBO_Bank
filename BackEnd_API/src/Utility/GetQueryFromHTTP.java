package Utility;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Sylvain on 09/01/2017.
 */
public class GetQueryFromHTTP {

    public GetQueryFromHTTP() {}

    public Map<String, String> getQuery(String query) {
        Map<String, String> result = new HashMap<String, String>();
        for (String param : query.split("&")) {
            String pair[] = param.split("=");
            if (pair.length>1) {
                result.put(pair[0], pair[1]);
            }else{
                result.put(pair[0], "");
            }
        }
        return result;
    }

}
