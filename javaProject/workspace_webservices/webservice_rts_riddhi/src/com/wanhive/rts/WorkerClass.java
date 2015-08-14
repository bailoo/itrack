/**********************************************************
 * Worker Thread Implementation, processes queued requests
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
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.channels.ServerSocketChannel;
import java.nio.channels.SocketChannel;
import java.nio.charset.Charset;
import java.nio.charset.CharsetDecoder;
import java.nio.charset.CharsetEncoder;
import java.util.ArrayList;
import java.util.List;

import com.wanhive.rts.handler.RequestHandler;


public class WorkerClass implements Runnable {
		
	public WorkerClass(RequestHandler handler) {
		this.handler=handler;		
	}

	@Override
	public void run() {
		ServerDataEvent dataEvent=null;
		int DataCnt=0;
		running=true;
		//System.out.println("b");
		while(true) {
			try {
			synchronized (queue) {
				while(queue.isEmpty() && running) {
					try {
						queue.wait();
					}catch (Exception e) {
					}
				}
				//If the thread has been stopped, get out of the infinite loop
				if(!running) break;
				//Remove the first job from the queue
				dataEvent=queue.remove(0);
				//System.out.println("c");
			}
			//Handle the request
			if(handler!=null)
			{
				//## set response here
			    //Charset charset = Charset.forName("ISO-8859-1");
			    //CharsetEncoder encoder = charset.newEncoder();
			    //CharsetDecoder decoder = charset.newDecoder();
			    //ByteBuffer buffer = ByteBuffer.allocate(512);
				
			    //SocketChannel client = serverChannel_w.accept();
			    //client.write(encoder.encode(CharBuffer.wrap("ok")));			
				//System.out.println("Transaction Started-Run");
				handler.handleProtocol(dataEvent);
				
				//Charset charset = Charset.forName("ISO-8859-1");
			    //CharsetEncoder encoder = charset.newEncoder();
			    //dataEvent.channel.write(encoder.encode(CharBuffer.wrap("ok")));
			    //System.out.println("A");
				DataCnt++;
			}
			
			//System.out.println("DataCnt="+DataCnt);
			if(DataCnt>100)
			{
				System.out.println("e");
				DataCnt=0;
			}
			//Just echo back
			//else
			//	dataEvent.server.send(dataEvent.channel, dataEvent.message.getBytes());
			}
			catch (Exception e) {
				//Application.writeLog("WorkerClass[run]:"+e.getMessage(), SystemLogger.SEVERE);
				e.printStackTrace();
			}
		}
		queue=null;
		//Application.writeLog("WorkerClass[run]: thread has been stopped", SystemLogger.WARN);
	}

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub
	}

	public void processData(byte[] data, int count) {
		synchronized(this.queue) {
			queue.add(new ServerDataEvent(data));
			//Notify that data is available
			queue.notify();
		}
	}
	
	public void stop() {
		running=false;
		synchronized (queue) {
			this.queue.notify();
		}
	}
	
	private List<ServerDataEvent> queue=new ArrayList<ServerDataEvent>();
	public RequestHandler handler;
	private boolean running=false;
}
