package org.tempuri;

public class ServiceSoapProxy implements org.tempuri.ServiceSoap {
  private String _endpoint = null;
  private org.tempuri.ServiceSoap serviceSoap = null;
  
  public ServiceSoapProxy() {
    _initServiceSoapProxy();
  }
  
  public ServiceSoapProxy(String endpoint) {
    _endpoint = endpoint;
    _initServiceSoapProxy();
  }
  
  private void _initServiceSoapProxy() {
    try {
      serviceSoap = (new org.tempuri.ServiceLocator()).getServiceSoap();
      if (serviceSoap != null) {
        if (_endpoint != null)
          ((javax.xml.rpc.Stub)serviceSoap)._setProperty("javax.xml.rpc.service.endpoint.address", _endpoint);
        else
          _endpoint = (String)((javax.xml.rpc.Stub)serviceSoap)._getProperty("javax.xml.rpc.service.endpoint.address");
      }
      
    }
    catch (javax.xml.rpc.ServiceException serviceException) {}
  }
  
  public String getEndpoint() {
    return _endpoint;
  }
  
  public void setEndpoint(String endpoint) {
    _endpoint = endpoint;
    if (serviceSoap != null)
      ((javax.xml.rpc.Stub)serviceSoap)._setProperty("javax.xml.rpc.service.endpoint.address", _endpoint);
    
  }
  
  public org.tempuri.ServiceSoap getServiceSoap() {
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap;
  }
  
  public java.lang.String get_Information(java.lang.String user_Id, java.lang.String vehicle_Id) throws java.rmi.RemoteException{
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap.get_Information(user_Id, vehicle_Id);
  }
  
  public java.lang.String get_Information_All(java.lang.String user_Id) throws java.rmi.RemoteException{
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap.get_Information_All(user_Id);
  }
  
  public java.lang.String get_Information_specified_time(java.lang.String user_Id, java.lang.String vehicle_Id, java.lang.String fromdate, java.lang.String todate) throws java.rmi.RemoteException{
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap.get_Information_specified_time(user_Id, vehicle_Id, fromdate, todate);
  }
  
  public java.lang.String get_login_logout_data(java.lang.String user_Id) throws java.rmi.RemoteException{
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap.get_login_logout_data(user_Id);
  }
  
  public java.lang.String get_Information_by_rto(java.lang.String user_Id, java.lang.String vehicle_Id) throws java.rmi.RemoteException{
    if (serviceSoap == null)
      _initServiceSoapProxy();
    return serviceSoap.get_Information_by_rto(user_Id, vehicle_Id);
  }
  
  
}