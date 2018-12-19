import API.DatabaseApi;
import Utility.Logs;

public class Main {

    public static void main(String[] args) {
        try {
            System.out.print("Launch API\n");
            new DatabaseApi(new Logs());
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}

