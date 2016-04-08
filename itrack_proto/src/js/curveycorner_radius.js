// JavaScript Document
      
      addEvent(window, "load", initCorners);
      
      function initCorners() {
      /*var settings = {
      tl: { radius: 10 },
      tr: { radius: 10 },
      bl: { radius: 10 },
      br: { radius: 10 },
      antiAlias: true
      }*/
      
      var settings1 = {
      tl: { radius: 5 },
      tr: { radius: 5 },
      bl: { radius: 0 },
      br: { radius: 0 },
      antiAlias: true
      }
      
      var settings2 = {
      tl: { radius: 5 },
      tr: { radius: 5 },
      bl: { radius: 0},
      br: { radius: 0 },
      antiAlias: true
      }
      
       var settings3 = {
      tl: { radius: 25 },
      tr: { radius: 5 },
      bl: { radius: 0},
      br: { radius: 0 },
      antiAlias: true
      } 
      
      var settings4= {
      tl: { radius: 0 },
      tr: { radius: 0 },
      bl: { radius: 1 },
      br: { radius: 1 },
      antiAlias: true
      }
    /*
    Usage:

    curvyCorners(settingsObj, selectorStr);
    curvyCorners(settingsObj, Obj1[, Obj2[, Obj3[, . . . [, ObjN]]]]);

    selectorStr ::= complexSelector [, complexSelector]...
    complexSelector ::= singleSelector[ singleSelector]
    singleSelector ::= idType | classType
    idType ::= #id
    classType ::= [tagName].className
    tagName ::= div|p|form|blockquote|frameset // others may work
    className : .name
    selector examples:
      #mydiv p.rounded
      #mypara
      .rounded   
    */
     
      //curvyCorners(settings, "#myBoxA");
      curvyCorners(settings1, ".allpageheading");
      curvyCorners(settings2, ".allpageheading2");
      curvyCorners(settings3, ".allpageheading3");
      curvyCorners(settings4, ".allpageheading4");
      /*curvyCorners(settings3, ".gradientH");*/ 
      
      }
