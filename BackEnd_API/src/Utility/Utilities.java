package Utility;

/**
 * Created by Sylvain on 09/01/2017.
 */
public class Utilities {

    private Logs _logs = null;
    private DatabaseConnection _db = null;
    private AdminDBConnection _admindb = null;
    private GetQueryFromHTTP _getQuery = null;

    public Utilities(Logs logs) {
        _logs = logs;

        //CREATE DB CONNECTION
        _db = new DatabaseConnection(_logs);
        _admindb = new AdminDBConnection(_logs);

        //CREATE GET QUERY OBJECT
        _getQuery = new GetQueryFromHTTP();
    }

    public DatabaseConnection getDBC() {
        return _db;
    }

    public AdminDBConnection getDBCA() {
        return _admindb;
    }

    public Logs getLogs() {
        return _logs;
    }

    public GetQueryFromHTTP getHTTPQuery() {
        return _getQuery;
    }

}
