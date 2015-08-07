package org.tempuri;

public class TrackSoapProxy implements org.tempuri.TrackSoap {
  private String _endpoint = null;
  private org.tempuri.TrackSoap trackSoap = null;
  
  public TrackSoapProxy() {
    _initTrackSoapProxy();
  }
  
  public TrackSoapProxy(String endpoint) {
    _endpoint = endpoint;
    _initTrackSoapProxy();
  }
  
  private void _initTrackSoapProxy() {
    try {
      trackSoap = (new org.tempuri.TrackLocator()).gettrackSoap();
      if (trackSoap != null) {
        if (_endpoint != null)
          ((javax.xml.rpc.Stub)trackSoap)._setProperty("javax.xml.rpc.service.endpoint.address", _endpoint);
        else
          _endpoint = (String)((javax.xml.rpc.Stub)trackSoap)._getProperty("javax.xml.rpc.service.endpoint.address");
      }
      
    }
    catch (javax.xml.rpc.ServiceException serviceException) {}
  }
  
  public String getEndpoint() {
    return _endpoint;
  }
  
  public void setEndpoint(String endpoint) {
    _endpoint = endpoint;
    if (trackSoap != null)
      ((javax.xml.rpc.Stub)trackSoap)._setProperty("javax.xml.rpc.service.endpoint.address", _endpoint);
    
  }
  
  public org.tempuri.TrackSoap getTrackSoap() {
    if (trackSoap == null)
      _initTrackSoapProxy();
    return trackSoap;
  }
  
  public java.lang.String helloWorld() throws java.rmi.RemoteException{
    if (trackSoap == null)
      _initTrackSoapProxy();
    return trackSoap.helloWorld();
  }
  
  public org.tempuri.GetVehicleDataResponseGetVehicleDataResult getVehicleData(java.lang.String vehicleno, java.lang.String authcode) throws java.rmi.RemoteException{
    if (trackSoap == null)
      _initTrackSoapProxy();
    return trackSoap.getVehicleData(vehicleno, authcode);
  }
  
  
}