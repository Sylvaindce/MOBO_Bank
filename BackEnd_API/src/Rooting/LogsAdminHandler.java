package Rooting;

import Utility.AdminDBConnection;
import Utility.Logs;
import Utility.Utilities;
import com.sun.net.httpserver.Headers;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import oracle.jdbc.OracleTypes;
import org.json.simple.JSONArray;

import javax.json.Json;
import javax.json.JsonObject;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.CallableStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Map;

/**
 * Created by Sylvain on 12/01/2017.
 */
public class LogsAdminHandler implements HttpHandler{

    private Logs _logs = null;
    private AdminDBConnection _dbadmin = null;
    private Utilities _util = null;

    private Integer userid = -1;

    private JSONArray idList = null;
    private JSONArray actionList = null;
    private JSONArray dateList = null;
    private JSONArray firstnameList = null;
    private JSONArray lastnameList = null;

    public LogsAdminHandler(Utilities util) {
        _util = util;
        _dbadmin = _util.getDBCA();
        _logs = _util.getLogs();

        idList = new JSONArray();
        actionList = new JSONArray();
        dateList = new JSONArray();
        firstnameList = new JSONArray();
        lastnameList = new JSONArray();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {
        _logs.print_log("ADMIN GET /logs");

        //GET INCOME PARAMETERS
        if (httpExchange.getRequestURI().getQuery() != null) {
            Map<String, String> result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("ADMIN FrontEnd -> . : "+result.toString());
            userid = Integer.parseInt(result.entrySet().iterator().next().getValue());
        }

        try {
            CallableStatement state = _dbadmin.getConnection().prepareCall("call admin_pkg.get_allauditlog(?,?)");
            state.setInt(1, userid);
            state.registerOutParameter(2, OracleTypes.CURSOR);
            state.execute();

            //MANAGE ANSWER
            ResultSet cursor = (ResultSet) state.getObject(2);

            idList.clear();
            actionList.clear();
            dateList.clear();
            firstnameList.clear();
            lastnameList.clear();

            while(cursor.next()) {
                idList.add(cursor.getInt("userid"));
                actionList.add(cursor.getString("description"));
                dateList.add(cursor.getString("auditdate"));
                firstnameList.add(cursor.getString("firstname"));
                lastnameList.add(cursor.getString("lastname"));
                _logs.print_log("ADMIN DB -> . : "+cursor.getInt("id")+" "+cursor.getString("description")+" "+cursor.getString("auditdate"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("userid", userid)
                .add("id", idList.toJSONString())
                .add("action", actionList.toJSONString())
                .add("date", dateList.toJSONString())
                .add("firstname", firstnameList.toJSONString())
                .add("lastname", lastnameList.toJSONString())
                .add("size", dateList.size())
                .build();

        //PREPARE ANSWER HEADER
        Headers h = httpExchange.getResponseHeaders();
        h.add("Content-Type", "application/json");
        h.add("Accept-Encoding", "UTF-8");
        h.add("Access-Control-Allow-Origin", "*");

        String response = json.toString();

        _logs.print_log("ADMIN FrontEnd <- . : "+ response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}
