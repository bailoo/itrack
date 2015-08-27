/**
 * ServiceSoap.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.4 Apr 22, 2006 (06:55:48 PDT) WSDL2Java emitter.
 */

package org.tempuri;

public interface ServiceSoap extends java.rmi.Remote {
    public java.lang.String get_Information(java.lang.String user_Id, java.lang.String vehicle_Id) throws java.rmi.RemoteException;
    public java.lang.String get_Information_All(java.lang.String user_Id) throws java.rmi.RemoteException;
    public java.lang.String get_Information_specified_time(java.lang.String user_Id, java.lang.String vehicle_Id, java.lang.String fromdate, java.lang.String todate) throws java.rmi.RemoteException;
    public java.lang.String get_login_logout_data(java.lang.String user_Id) throws java.rmi.RemoteException;
    public java.lang.String get_Information_by_rto(java.lang.String user_Id, java.lang.String vehicle_Id) throws java.rmi.RemoteException;
}
