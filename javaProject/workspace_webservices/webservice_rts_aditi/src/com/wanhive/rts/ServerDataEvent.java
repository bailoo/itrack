/**********************************************************
 * Data Handling Object Implementation
 * Copyright (C) 2011  Amit Kumar(amitkriit@gmail.com)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ***********************************************************/
package com.wanhive.rts;

import java.nio.channels.ServerSocketChannel;
import java.nio.channels.SocketChannel;


public class ServerDataEvent {
	public ServerDataEvent(byte[] data) {
		//this.server=server;
		//this.channel=channel;
		//this.connectionType=connectionType;
		String clientMessage=new String(data);
		//Application.writeLog("Message from ["+clientSocket.getInetAddress()+":"+clientSocket.getPort()+", "+clientId+"]: "+clientMessage);
		
		//Store the message for future use
		this.message=clientMessage;
	}
	
	//public TransactionServer server;
	//public SocketChannel channel;
	
	public String message;
	//Request parameters will be stored here after filtering
	public String[] requestParams;
	
	public ConnectionType connectionType;
}
