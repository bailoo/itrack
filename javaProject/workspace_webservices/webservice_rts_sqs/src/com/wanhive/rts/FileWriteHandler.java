package com.wanhive.rts;

import java.io.RandomAccessFile;

public class FileWriteHandler {
	
	public FileWriteHandler(RandomAccessFile RFile, String RFileName,Long CreateTime, Long UpdateTime,StringBuffer StrBuf) {
		this.RFile=RFile;
		this.RFileName=RFileName;
		this.UpdateTime = UpdateTime;
		this.StrBuf = StrBuf;
		this.CreateTime = CreateTime;
	}
	
	public RandomAccessFile RFile;
	public String RFileName;
	public Long CreateTime;
	public Long UpdateTime;
	public StringBuffer StrBuf;
}
