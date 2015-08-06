/**
 * TrackSoap.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.4 Apr 22, 2006 (06:55:48 PDT) WSDL2Java emitter.
 */

package org.tempuri;

public interface TrackSoap extends java.rmi.Remote {
    public org.tempuri.GetVehicleLatestDataResponseGetVehicleLatestDataResult getVehicleLatestData(java.lang.String vehicleno, java.lang.String authcode) throws java.rmi.RemoteException;
    public org.tempuri.GetVehiclesDetailsResponseGetVehiclesDetailsResult getVehiclesDetails(java.lang.String vehicleno, java.util.Calendar fromDateTime, java.util.Calendar toDatetime, int dataInterval, java.lang.String authcode) throws java.rmi.RemoteException;
}
