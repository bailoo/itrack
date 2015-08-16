/**
 * GetVehiclesDetails.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis 1.4 Apr 22, 2006 (06:55:48 PDT) WSDL2Java emitter.
 */

package org.tempuri;

public class GetVehiclesDetails  implements java.io.Serializable {
    private java.lang.String vehicleno;

    private java.util.Calendar fromDateTime;

    private java.util.Calendar toDatetime;

    private int dataInterval;

    private java.lang.String authcode;

    public GetVehiclesDetails() {
    }

    public GetVehiclesDetails(
           java.lang.String vehicleno,
           java.util.Calendar fromDateTime,
           java.util.Calendar toDatetime,
           int dataInterval,
           java.lang.String authcode) {
           this.vehicleno = vehicleno;
           this.fromDateTime = fromDateTime;
           this.toDatetime = toDatetime;
           this.dataInterval = dataInterval;
           this.authcode = authcode;
    }


    /**
     * Gets the vehicleno value for this GetVehiclesDetails.
     * 
     * @return vehicleno
     */
    public java.lang.String getVehicleno() {
        return vehicleno;
    }


    /**
     * Sets the vehicleno value for this GetVehiclesDetails.
     * 
     * @param vehicleno
     */
    public void setVehicleno(java.lang.String vehicleno) {
        this.vehicleno = vehicleno;
    }


    /**
     * Gets the fromDateTime value for this GetVehiclesDetails.
     * 
     * @return fromDateTime
     */
    public java.util.Calendar getFromDateTime() {
        return fromDateTime;
    }


    /**
     * Sets the fromDateTime value for this GetVehiclesDetails.
     * 
     * @param fromDateTime
     */
    public void setFromDateTime(java.util.Calendar fromDateTime) {
        this.fromDateTime = fromDateTime;
    }


    /**
     * Gets the toDatetime value for this GetVehiclesDetails.
     * 
     * @return toDatetime
     */
    public java.util.Calendar getToDatetime() {
        return toDatetime;
    }


    /**
     * Sets the toDatetime value for this GetVehiclesDetails.
     * 
     * @param toDatetime
     */
    public void setToDatetime(java.util.Calendar toDatetime) {
        this.toDatetime = toDatetime;
    }


    /**
     * Gets the dataInterval value for this GetVehiclesDetails.
     * 
     * @return dataInterval
     */
    public int getDataInterval() {
        return dataInterval;
    }


    /**
     * Sets the dataInterval value for this GetVehiclesDetails.
     * 
     * @param dataInterval
     */
    public void setDataInterval(int dataInterval) {
        this.dataInterval = dataInterval;
    }


    /**
     * Gets the authcode value for this GetVehiclesDetails.
     * 
     * @return authcode
     */
    public java.lang.String getAuthcode() {
        return authcode;
    }


    /**
     * Sets the authcode value for this GetVehiclesDetails.
     * 
     * @param authcode
     */
    public void setAuthcode(java.lang.String authcode) {
        this.authcode = authcode;
    }

    private java.lang.Object __equalsCalc = null;
    public synchronized boolean equals(java.lang.Object obj) {
        if (!(obj instanceof GetVehiclesDetails)) return false;
        GetVehiclesDetails other = (GetVehiclesDetails) obj;
        if (obj == null) return false;
        if (this == obj) return true;
        if (__equalsCalc != null) {
            return (__equalsCalc == obj);
        }
        __equalsCalc = obj;
        boolean _equals;
        _equals = true && 
            ((this.vehicleno==null && other.getVehicleno()==null) || 
             (this.vehicleno!=null &&
              this.vehicleno.equals(other.getVehicleno()))) &&
            ((this.fromDateTime==null && other.getFromDateTime()==null) || 
             (this.fromDateTime!=null &&
              this.fromDateTime.equals(other.getFromDateTime()))) &&
            ((this.toDatetime==null && other.getToDatetime()==null) || 
             (this.toDatetime!=null &&
              this.toDatetime.equals(other.getToDatetime()))) &&
            this.dataInterval == other.getDataInterval() &&
            ((this.authcode==null && other.getAuthcode()==null) || 
             (this.authcode!=null &&
              this.authcode.equals(other.getAuthcode())));
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
        if (getVehicleno() != null) {
            _hashCode += getVehicleno().hashCode();
        }
        if (getFromDateTime() != null) {
            _hashCode += getFromDateTime().hashCode();
        }
        if (getToDatetime() != null) {
            _hashCode += getToDatetime().hashCode();
        }
        _hashCode += getDataInterval();
        if (getAuthcode() != null) {
            _hashCode += getAuthcode().hashCode();
        }
        __hashCodeCalc = false;
        return _hashCode;
    }

    // Type metadata
    private static org.apache.axis.description.TypeDesc typeDesc =
        new org.apache.axis.description.TypeDesc(GetVehiclesDetails.class, true);

    static {
        typeDesc.setXmlType(new javax.xml.namespace.QName("http://tempuri.org/", ">GetVehiclesDetails"));
        org.apache.axis.description.ElementDesc elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("vehicleno");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "vehicleno"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://www.w3.org/2001/XMLSchema", "string"));
        elemField.setMinOccurs(0);
        elemField.setNillable(false);
        typeDesc.addFieldDesc(elemField);
        elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("fromDateTime");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "FromDateTime"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://www.w3.org/2001/XMLSchema", "dateTime"));
        elemField.setNillable(false);
        typeDesc.addFieldDesc(elemField);
        elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("toDatetime");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "ToDatetime"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://www.w3.org/2001/XMLSchema", "dateTime"));
        elemField.setNillable(false);
        typeDesc.addFieldDesc(elemField);
        elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("dataInterval");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "DataInterval"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://www.w3.org/2001/XMLSchema", "int"));
        elemField.setNillable(false);
        typeDesc.addFieldDesc(elemField);
        elemField = new org.apache.axis.description.ElementDesc();
        elemField.setFieldName("authcode");
        elemField.setXmlName(new javax.xml.namespace.QName("http://tempuri.org/", "Authcode"));
        elemField.setXmlType(new javax.xml.namespace.QName("http://www.w3.org/2001/XMLSchema", "string"));
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
