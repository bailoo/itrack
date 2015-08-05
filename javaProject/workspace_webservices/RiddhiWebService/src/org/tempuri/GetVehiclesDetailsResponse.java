/**
 * GetVehiclesDetailsResponse.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.4 Apr 22, 2006 (06:55:48 PDT) WSDL2Java emitter.
 */

package org.tempuri;

public class GetVehiclesDetailsResponse  implements java.io.Serializable {
    private org.tempuri.GetVehiclesDetailsResponseGetVehiclesDetailsResult getVehiclesDetailsResult;

    public GetVehiclesDetailsResponse() {
    }

    public GetVehiclesDetailsResponse(
           org.tempuri.GetVehiclesDetailsResponseGetVehiclesDetailsResult getVehiclesDetailsResult) {
           this.getVehiclesDetailsResult = getVehiclesDetailsResult;
    }


    /**
     * Gets the getVehiclesDetailsResult value for this GetVehiclesDetailsResponse.
     * 
     * @return getVehiclesDetailsResult
     */
    public org.tempuri.GetVehiclesDetailsResponseGetVehiclesDetailsResult getGetVehiclesDetailsResult() {
        return getVehiclesDetailsResult;
    }


    /**
     * Sets the getVehiclesDetailsResult value for this GetVehiclesDetailsResponse.
     * 
     * @param getVehiclesDetailsResult
     */
    public void setGetVehiclesDetailsResult(org.tempuri.GetVehiclesDetailsResponseGetVehiclesDetailsResult getVehiclesDetailsResult) {
        this.getVehiclesDetailsResult = getVehiclesDetailsResult;
    }

    private java.lang.Object __equalsCalc = null;
    public synchronized boolean equals(java.lang.Object obj) {
        if (!(obj instanceof GetVehiclesDetailsResponse)) return false;
        GetVehiclesDetailsResponse other = (GetVehiclesDetailsResponse) obj;
        if (obj == null) return false;
        if (this == obj) return true;
        if (__equalsCalc != null) {
            return (__equalsCalc == obj);
        }
        __equalsCalc = obj;
        boolean _equals;
        _equals = true && 
            ((this.getVehiclesDetailsResult==null && other.getGetVehiclesDetailsResult()==null) || 
             (this.getVehiclesDetailsResult!=null &&
              this.getVehiclesDetailsResult.equals(other.getGetVehiclesDetailsResult())));
        __equalsCalc = null;
        return _equals;
    }

    private boolean __hashCodeCalc = false;
    public synchronized int hashCode() {
        if (__hashCodeCalc) {
            return 0;
        }
        __hashCodeCalc = true;
        int _hashCode = 1;
        if (getGetVehiclesDetailsResult() != null) {
            _hashCode += getGetVehiclesDetailsResult().hashCode();
        }
        __hashCodeCalc = false;
        return _hashCode;
    }

    // Type metadata
    private static org.apache.axis.description.TypeDesc typeDesc =
        new org.apache.axis.description.TypeDesc(GetVehiclesDetailsResponse.class, true);

    static {
        typeDesc.setXmlType(new javax.xml.namespace.QName("http://tempuri.org/", ">GetVehiclesDetailsResponse"));
        org.apache.axis.description.ElementDesc elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("getVehiclesDetailsResult");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "GetVehiclesDetailsResult"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://tempuri.org/", ">>GetVehiclesDetailsResponse>GetVehiclesDetailsResult"));
        elemField.setMinOccurs(0);
        elemField.setNillable(false);
        typeDesc.addFieldDesc(elemField);
    }

    /**
     * Return type metadata object
     */
    public static org.apache.axis.description.TypeDesc getTypeDesc() {
        return typeDesc;
    }

    /**
     * Get Custom Serializer
     */
    public static org.apache.axis.encoding.Serializer getSerializer(
           java.lang.String mechType, 
           java.lang.Class _javaType,  
           javax.xml.namespace.QName _xmlType) {
        return 
          new  org.apache.axis.encoding.ser.BeanSerializer(
            _javaType, _xmlType, typeDesc);
    }

    /**
     * Get Custom Deserializer
     */
    public static org.apache.axis.encoding.Deserializer getDeserializer(
           java.lang.String mechType, 
           java.lang.Class _javaType,  
           javax.xml.namespace.QName _xmlType) {
        return 
          new  org.apache.axis.encoding.ser.BeanDeserializer(
            _javaType, _xmlType, typeDesc);
    }

}
