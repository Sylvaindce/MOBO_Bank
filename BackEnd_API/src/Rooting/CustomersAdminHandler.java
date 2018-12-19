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
public class CustomersAdminHandler implements HttpHandler {

    private Logs _logs = null;
    private AdminDBConnection _dbadmin = null;
    private Utilities _util = null;

    private Integer userid = -1;

    private JSONArray idList = null;
    private JSONArray passwordList = null;
    private JSONArray firstnameList = null;
    private JSONArray lastnameList = null;
    private JSONArray addressList = null;
    private JSONArray emailList = null;
    private JSONArray phoneList = null;
    private JSONArray createList = null;
    private JSONArray loginList = null;
    private JSONArray amountList = null;
    private JSONArray accountList = null;

    public CustomersAdminHandler(Utilities util) {
        _util = util;
        _dbadmin = _util.getDBCA();
        _logs = _util.getLogs();

        idList = new JSONArray();
        passwordList = new JSONArray();
        firstnameList = new JSONArray();
        lastnameList = new JSONArray();
        addressList = new JSONArray();
        emailList = new JSONArray();
        phoneList = new JSONArray();
        createList = new JSONArray();
        loginList = new JSONArray();
        amountList = new JSONArray();
        accountList = new JSONArray();
    }

    @Override
    public void handle(HttpExchange httpExchange) throws IOException {
        _logs.print_log("ADMIN GET /customers");

        //GET INCOME PARAMETERS
        if (httpExchange.getRequestURI().getQuery() != null) {
            Map<String, String> result = _util.getHTTPQuery().getQuery(httpExchange.getRequestURI().getQuery());
            _logs.print_log("ADMIN FrontEnd -> . : "+result.toString());
            userid = Integer.parseInt(result.entrySet().iterator().next().getValue());
        }

        try {
            CallableStatement state = _dbadmin.getConnection().prepareCall("call admin_pkg.get_alluser(?,?)");
            state.setInt(1, userid);
            state.registerOutParameter(2, OracleTypes.CURSOR);
            state.execute();

            //MANAGE ANSWER
            ResultSet cursor = (ResultSet) state.getObject(2);

            idList.clear();
            passwordList.clear();
            firstnameList.clear();
            lastnameList.clear();
            addressList.clear();
            emailList.clear();
            phoneList.clear();
            createList.clear();
            loginList.clear();
            amountList.clear();
            accountList.clear();

            while(cursor.next()) {
                idList.add(cursor.getInt("id"));
                passwordList.add(cursor.getString("password"));
                firstnameList.add(cursor.getString("firstname"));
                lastnameList.add(cursor.getString("lastname"));
                addressList.add(cursor.getString("address"));
                emailList.add(cursor.getString("email"));
                phoneList.add(cursor.getString("phonenumber"));
                createList.add(cursor.getString("creationdate"));
                loginList.add(cursor.getString("lastlogin"));
                accountList.add(cursor.getString("accountnumber"));
                amountList.add(cursor.getString("balance"));
                _logs.print_log("ADMIN DB -> . : "+cursor.getInt("id")+" "+cursor.getString("password")+" "+cursor.getString("firstname")+" "+cursor.getString("lastname")+" "+cursor.getString("address")+" "+cursor.getString("email")+" "+cursor.getString("phonenumber")+" "+cursor.getString("creationdate")+" "+cursor.getString("lastlogin")+" "+cursor.getString("balance")+" "+cursor.getString("accountnumber"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        //Create JSON Object
        JsonObject json = Json.createObjectBuilder()
                .add("userid", userid)
                .add("id", idList.toJSONString())
                .add("password", passwordList.toJSONString())
                .add("firstname", firstnameList.toJSONString())
                .add("lastname", lastnameList.toJSONString())
                .add("address", addressList.toJSONString())
                .add("email", emailList.toJSONString())
                .add("phone", phoneList.toJSONString())
                .add("creation", createList.toJSONString())
                .add("last", loginList.toJSONString())
                .add("account", accountList.toJSONString())
                .add("balance", amountList.toJSONString())
                .add("size", loginList.size())
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
