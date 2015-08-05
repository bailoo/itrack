/**
 * TrackLocator.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.4 Apr 22, 2006 (06:55:48 PDT) WSDL2Java emitter.
 */

package org.tempuri;

public class TrackLocator extends org.apache.axis.client.Service implements org.tempuri.Track {

    public TrackLocator() {
    }


    public TrackLocator(org.apache.axis.EngineConfiguration config) {
        super(config);
    }

    public TrackLocator(java.lang.String wsdlLoc, javax.xml.namespace.QName sName) throws javax.xml.rpc.ServiceException {
        super(wsdlLoc, sName);
    }

    // Use to get a proxy class for trackSoap
    private java.lang.String trackSoap_address = "http://220.226.105.72/RtrackWebservice/track.asmx";

    public java.lang.String gettrackSoapAddress() {
        return trackSoap_address;
    }

    // The WSDD service name defaults to the port name.
    private java.lang.String trackSoapWSDDServiceName = "trackSoap";

    public java.lang.String gettrackSoapWSDDServiceName() {
        return trackSoapWSDDServiceName;
    }

    public void settrackSoapWSDDServiceName(java.lang.String name) {
        trackSoapWSDDServiceName = name;
    }

    public org.tempuri.TrackSoap gettrackSoap() throws javax.xml.rpc.ServiceException {
       java.net.URL endpoint;
        try {
            endpoint = new java.net.URL(trackSoap_address);
        }
        catch (java.net.MalformedURLException e) {
            throw new javax.xml.rpc.ServiceException(e);
        }
        return gettrackSoap(endpoint);
    }

    public org.tempuri.TrackSoap gettrackSoap(java.net.URL portAddress) throws javax.xml.rpc.ServiceException {
        try {
            org.tempuri.TrackSoapStub _stub = new org.tempuri.TrackSoapStub(portAddress, this);
            _stub.setPortName(gettrackSoapWSDDServiceName());
            return _stub;
        }
        catch (org.apache.axis.AxisFault e) {
            return null;
        }
    }

    public void settrackSoapEndpointAddress(java.lang.String address) {
        trackSoap_address = address;
    }

    /**
     * For the given interface, get the stub implementation.
     * If this service has no port for the given interface,
     * then ServiceException is thrown.
     */
    public java.rmi.Remote getPort(Class serviceEndpointInterface) throws javax.xml.rpc.ServiceException {
        try {
            if (org.tempuri.TrackSoap.class.isAssignableFrom(serviceEndpointInterface)) {
                org.tempuri.TrackSoapStub _stub = new org.tempuri.TrackSoapStub(new java.net.URL(trackSoap_address), this);
                _stub.setPortName(gettrackSoapWSDDServiceName());
                return _stub;
            }
        }
        catch (java.lang.Throwable t) {
            throw new javax.xml.rpc.ServiceException(t);
        }
        throw new javax.xml.rpc.ServiceException("There is no stub implementation for the interface:  " + (serviceEndpointInterface == null ? "null" : serviceEndpointInterface.getName()));
    }

    /**
     * For the given interface, get the stub implementation.
     * If this service has no port for the given interface,
     * then ServiceException is thrown.
     */
    public java.rmi.Remote getPort(javax.xml.namespace.QName portName, Class serviceEndpointInterface) throws javax.xml.rpc.ServiceException {
        if (portName == null) {
            return getPort(serviceEndpointInterface);
        }
        java.lang.String inputPortName = portName.getLocalPart();
        if ("trackSoap".equals(inputPortName)) {
            return gettrackSoap();
        }
        else  {
            java.rmi.Remote _stub = getPort(serviceEndpointInterface);
            ((org.apache.axis.client.Stub) _stub).setPortName(portName);
            return _stub;
        }
    }

    public javax.xml.namespace.QName getServiceName() {
        return new javax.xml.namespace.QName("http://tempuri.org/", "track");
    }

    private java.util.HashSet ports = null;

    public java.util.Iterator getPorts() {
        if (ports == null) {
            ports = new java.util.HashSet();
            ports.add(new javax.xml.namespace.QName("http://tempuri.org/", "trackSoap"));
        }
        return ports.iterator();
    }

    /**
    * Set the endpoint address for the specified port name.
    */
    public void setEndpointAddress(java.lang.String portName, java.lang.String address) throws javax.xml.rpc.ServiceException {
        
if ("trackSoap".equals(portName)) {
            settrackSoapEndpointAddress(address);
        }
        else 
{ // Unknown Port Name
            throw new javax.xml.rpc.ServiceException(" Cannot set Endpoint Address for Unknown Port" + portName);
        }
    }

    /**
    * Set the endpoint address for the specified port name.
    */
    public void setEndpointAddress(javax.xml.namespace.QName portName, java.lang.String address) throws javax.xml.rpc.ServiceException {
        setEndpointAddress(portName.getLocalPart(), address);
    }

}
