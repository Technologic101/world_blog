/**
 * Global Javascript file, use this to bootstrap the site
 */

jQuery(document).ready( function ($) {

  //Create Cesium widget
  viewer = new Cesium.Viewer('body', {
    animation       : false,
    baseLayerPicker : false,
    fullscreenButton: false,
    geocoder        : false,
    sceneModePicker : false,
    timeline        : false,
    navigationInstructionsInitiallyVisible : false,
    skyBox : new Cesium.SkyBox({
      sources : {
        positiveX : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_px.jpg',
        negativeX : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_mx.jpg',
        positiveY : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_py.jpg',
        negativeY : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_my.jpg',
        positiveZ : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_pz.jpg',
        negativeZ : 'wp-content/themes/envoy/js/cesium/Source/Assets/Textures/SkyBox/TychoSkymapII.t3_08192x04096_80_mz.jpg'
      }
    })
  });

  var scene = viewer.scene;

  //Add the moon for fun
  viewer.cesiumWidget.scene.moon = new Cesium.Moon();

  //Render each post as an entity on the map
  for (var id in acf_fields) {
    if (acf_fields.hasOwnProperty(id)) {
      var obj = acf_fields[id];

      viewer.entities.add({
          id        : id,
          name      : obj.name,
          position  : Cesium.Cartesian3.fromDegrees(obj.longitude, obj.latitude),
          billboard : {
              image : obj.icon,
              scale : 2.5
          },
          longitude : obj.longitude,
          latitude  : obj.latitude,
          url       : siteurl.siteurl + '/' + obj.url
      });
    }
  }

  console.log(acf_fields);
  console.log(viewer.entities.values);

  // Loading and Flying to posts
  var content = $('#content');

  // Define handler for picking objects and flying to them on selection
  var handler = new Cesium.ScreenSpaceEventHandler(viewer.canvas);

  handler.setInputAction( function(click) {
    var obj = scene.pick(click.position);
    if(Cesium.defined(obj) && obj.id.url ) {
      var location = Cesium.Cartesian3.fromDegrees(obj.id.longitude, obj.id.latitude, 1500),
          url = obj.id.url;
      loadPost(url, location);
    }
  },
    Cesium.ScreenSpaceEventType.LEFT_DOWN
  );

  // Set up AJAX handlers for loading posts

  $('#header a').on('click', function(e) {
    var url = $(this).attr('href');
    e.preventDefault();
    alert(viewer.entities.values.find( function(el, index, array) {
      return el.url == url;
    }));
    alert('after');
    console.log(result);
  });

  function loadPost(url, location) {
    var url = url + ' #content > article';

    content.removeClass('show');
    console.log(url);
    $('#loading').addClass('in-progress');
    content.load(url, function () {
      $('#loading').removeClass('in-progress');
      content.addClass('show');
    });
    if (url) {
      viewer.camera.flyTo({
        destination : location
      });
    }
  }

  // Close post button

  $('body').on('click', '.close', function () {
    content.removeClass('show');
  });

});

