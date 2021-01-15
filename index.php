<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <title>Present With Face | Web Application | Real Time Attendance System |</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta name="description" content="Chandigarh University College Project | A online platform to mark attendance just by recognizing your face | Developed By Apurbo Mitra" />
        <meta name="application-name" content="Present With Face">
        <meta name="theme-color" content="#040c46">
        <meta name="author" content="Apurbo Mitra">
        <style>
                #container {
                    margin: 0px auto;
                    /*width: 500px;*/
                    /*height: 375px;*/
                    border: 10px #333 solid;
                }
                #videoElement {
                    top: 0px;
                    left:0;
                    width: 100%;
                    height: 470px;
                }
                #overlay, .overlay {
                    position: absolute;
                    top: 0px;
                    left: 30%;
            }
        </style>
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body style="    background-color: #202034;">
        <div class="row">
            <div class="col-xl-12">
                <div id="divAlert"  class="alert alert-success" role="alert" style="display: none; position: fixed;top: 100px; /*width: 100%;*/ right: 2%;z-index: 99;">
                    <span class="msg"></span>
                </div>
                <!-- <div id="container"> -->
            <canvas id="canvas" class="overlay"></canvas>
            <video autoplay="true" id="videoElement"></video>
            </div>
            <!-- <div class="col-md-12">
               <div id="names"></div> 
            </div> -->
        </div>
            
                   
            <!-- </div> -->
            
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script defer src="face-api.js"></script>
        <script>

          $(document).ready(function(){

           

                let video = document.querySelector("#videoElement");
                let currentStream;
                let displaySize;
                var names = [];

                if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
                    console.log("Let's start")
                    navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                    video.srcObject = stream;
                    })
                    .catch(function (err0r) {
                    console.log("Something went wrong!");
                    });
                }
                else{
                    alert("No");
                }

                 
                
                $("#videoElement").bind("loadedmetadata", function(){
                    displaySize = { width:this.scrollWidth, height: this.scrollHeight }

                    async function detect(){

                        const MODEL_URL = './models'

                        await faceapi.loadSsdMobilenetv1Model(MODEL_URL)
                        await faceapi.loadFaceLandmarkModel(MODEL_URL)
                        await faceapi.loadFaceRecognitionModel(MODEL_URL)

                        let canvas = $("#canvas").get(0);

                        facedetection = setInterval(async () =>{

                            let fullFaceDescriptions = await faceapi.detectAllFaces(video).withFaceLandmarks().withFaceDescriptors()
                            let canvas = $("#canvas").get(0);
                            faceapi.matchDimensions(canvas, displaySize)

                            const fullFaceDescription = faceapi.resizeResults(fullFaceDescriptions, displaySize)
                            // faceapi.draw.drawDetections(canvas, fullFaceDescriptions)

                            const labels = ['Apurbo','Nikhil','Pranjal','Shivam']

                            const labeledFaceDescriptors = await Promise.all(
                                labels.map(async label => {
                                    // fetch image data from urls and convert blob to HTMLImage element
                                    const imgUrl = `./labeled_images/${label}/1.jpg`
                                    const img = await faceapi.fetchImage(imgUrl)
                                    
                                    // detect the face with the highest score in the image and compute it's landmarks and face descriptor
                                    const fullFaceDescription = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                                    
                                    if (!fullFaceDescription) {
                                    throw new Error(`no faces detected for ${label}`)
                                    }
                                    
                                    const faceDescriptors = [fullFaceDescription.descriptor]
                                    return new faceapi.LabeledFaceDescriptors(label, faceDescriptors)
                                })
                            );

                            const maxDescriptorDistance = 0.6
                            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, maxDescriptorDistance)

                            const results = fullFaceDescriptions.map(fd => faceMatcher.findBestMatch(fd.descriptor))

                            results.forEach((bestMatch, i) => {
                                const box = fullFaceDescriptions[i].detection.box
                                const text = bestMatch.toString()
                                const text_name = bestMatch._label
                                const drawBox = new faceapi.draw.DrawBox(box, { label: text })
                                drawBox.draw(canvas)
                                if (text_name !== "unknown") {
                                      if (names.length > 0) {
                                        var flag =0;
                                        for(var i = 0; i <= names.length; i++){
                                            if (names[i] === text_name) {
                                                flag = 1;
                                            }
                                        }
                                        if(flag == 0){
                                            set(text_name);
                                            names.push(text_name);
                                        }
                                        else{
                                            $('.alert').addClass('alert-danger');
                                            $('.alert .msg').text(text_name+" yor attendance is already been marked");
                                            $('#divAlert').show();
                                            $('#divAlert').delay(2000).fadeOut(1000);
                                        }
                                    }
                                    else{
                                        set(text_name);
                                        names.push(text_name)  
                                    }
                                    // console.log(names);
                                    // $('#names').html(names)  
                                }
                            })
                        },3000);
                        console.log(displaySize)
                    }
                    detect()
                });   
            setInterval(function(){
                names = [];
            },60000)
          })  

        
            
            function set(text_name){
                $.ajax({
                  type: 'post',
                  url:'helpers/ajax_set.php',
                  data: {name: text_name},
                  success: function(data){
                    // console.log(data);
                    var jsonData = $.parseJSON(data);
                    if(jsonData.status === "success"){
                      $('.alert').removeClass('alert-danger');
                      $('.alert').addClass('alert-success');
                      $('.alert .msg').text(jsonData.message);
                      $('#divAlert').show();
                      $('#divAlert').delay(5000).fadeOut(2000);
                    } 
                    else{
                      $('.alert').removeClass('alert-success');
                      $('.alert').addClass('alert-danger');
                      $('.alert .msg').text(jsonData.message);
                      // console.log(jsonData.err);
                      $('#divAlert').show();
                      $('#divAlert').delay(5000).fadeOut(2000);
                    }
                  },
                  error: function(data){
                    console.log(data);
                  }
                });
            }

           // setInterval(function set(){
           //      $.ajax({
           //        type: 'post',
           //        url:'helpers/ajax_get.php',
           //        success: function(data){
           //          // console.log(data);
           //          $('#names').html(data)
           //        },
           //        error: function(data){
           //          console.log(data);
           //        }
           //      });
           //  },20000);
        
</script>
    </body>
</html>