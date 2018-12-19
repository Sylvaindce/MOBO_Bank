package Rooting;

import Utility.DatabaseConnection;
import Utility.Logs;
import Utility.Utilities;
import com.sun.net.httpserver.Headers;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;
import oracle.jdbc.OracleTypes;

import javax.json.Json;
import javax.json.JsonObject;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.CallableStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Types;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Sylvain on 16/12/2016.
 */
public class UserHandler implements HttpHandler {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private Utilities _util = null;

    private Integer userid = -1;
    private String firstname = "";
    private String lastname = "";
    private String password = "";
    private String email = "";
    private String phone = "";
    private String address = "";
    private String accountNumber = "";

    public UserHandler(Utilities util) {
        _util = util;
        _db = _util.getDBC();
        _logs = _util.getLogs();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {

        _logs.print_log("GET /user");

        //GET INCOME PARAMETERS
        if (httpExchange.getRequestURI().getQuery() != null) {
            Map<String, String> result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("FrontEnd -> . : "+result.toString());
            userid = Integer.parseInt(result.entrySet().iterator().next().getValue());
        }

        //CHECK DATA AND CONNECT TO DB

        try {
            CallableStatement state = _db.getConnection().prepareCall("call client_pkg.get_information(?,?)");
            state.setInt(1, userid);
            state.registerOutParameter(2, OracleTypes.CURSOR);
            state.execute();

            //MANAGE ANSWER
            ResultSet cursor = (ResultSet) state.getObject(2);

            cursor.next();
            firstname = cursor.getString("Firstname");
            lastname = cursor.getString("lastname");
            password = cursor.getString("password");
            email = cursor.getString("email");
            phone = cursor.getString("phonenumber");
            address = cursor.getString("address");
            accountNumber = cursor.getString("accountNumber");
            password = cursor.getString("password");

            _logs.print_log("DB -> . : "+firstname + " "+ lastname+" "+ password+" "+ email+" "+ phone+" "+ address+" "+ accountNumber+" "+password);
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("firstname", firstname)
                .add("lastname", lastname)
                .add("email", email)
                .add("phone", phone)
                .add("address", address)
                .add("account", accountNumber)
                .add("userid", userid)
                .add("pass", password)
                .build();

        //PREPARE ANSWER HEADER
        Headers h = httpExchange.getResponseHeaders();
        h.add("Content-Type", "application/json");
        h.add("Accept-Encoding", "UTF-8");
        h.add("Access-Control-Allow-Origin", "*");

        String response = json.toString();

        _logs.print_log("FrontEnd <- . : "+ response);

        httpExchange.sendResponseHeaders(200, response.length());
        OutputStream os = httpExchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}