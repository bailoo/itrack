/**********************************************************
 * Changes in Connection parameters and properties
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

import java.nio.channels.SocketChannel;

public class ChangeRequest {
	//Register the channel with ops
	public static final int REGISTER = 1;
	//Change the ops on the already registered channel
	public static final int CHANGEOPS = 2;
	//Change the Connection Type
	public static final int CONFIGURE=3;
	
	public SocketChannel socket;
	public int type;
	public int ops;
	public ConnectionType connectionType;

	public ChangeRequest(SocketChannel socket, int type, int ops) {
		this.socket = socket;
		this.type = type;
		this.ops = ops;
		this.connectionType=null;
	}
	
	public ChangeRequest(SocketChannel socket, int type, ConnectionType connectionType) {
		this.socket = socket;
		this.type = type;
		this.ops = 0;
		this.connectionType=connectionType;
	}
}
