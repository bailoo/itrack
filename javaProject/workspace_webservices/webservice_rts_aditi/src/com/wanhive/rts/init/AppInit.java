package com.wanhive.rts.init;
import java.nio.channels.SocketChannel;

import com.wanhive.rts.alert_module;
import com.wanhive.rts.utils.Application;

/**
 * Servlet implementation class AppInit
 */
public class AppInit {	
	
	public static void main(String[] args) {
		try {
			//System.out.println("test1");
			Application.init("app.lcf");
			//System.out.println("test2");
			
			/*//######## GET ESCALATION DETAIL
			System.out.println("BEFORE GET ESCALATION DETAIL");
			alert_module.get_escalation_detail();*/

		}
		catch (Exception e) {
			System.out.println("ClientInit[main]: "+e.getMessage());
		}
	}
}
