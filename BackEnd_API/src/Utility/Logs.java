package Utility;

import java.text.SimpleDateFormat;
import java.util.Calendar;

/**
 * Created by Sylvain on 16/12/2016.
 */
public class Logs {
    private SimpleDateFormat time_format = null;

    public Logs() {
        time_format = new SimpleDateFormat("YYYY-MM-dd HH:mm:ss");
    }

    public void print_log(String comments) {
        Calendar current_time = Calendar.getInstance();
        System.out.print("["+ time_format.format(current_time.getTime()) + "] " + comments + "\n");
    }

}
