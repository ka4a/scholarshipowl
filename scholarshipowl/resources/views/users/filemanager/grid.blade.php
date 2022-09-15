<div class="clearfix"></div>
<form method="post" multipart enctype='multipart/form-data' action="/files/upload">
    <input type="file" name="file" id="file" class="filestyle col-xs-12 col-md-6" data-buttonBefore="true" data-placeholder="No file chosen" />
    <input class="col-xs-12 col-md-6 inputFileDescription" type="text" name="description" id="desctription" placeholder="Optional description"/>
    <input class="uploadFile" type="submit" name="submit" id="submit" value="Upload"/>
</form>
<div class="clearfix pushDown20"></div>
<ul class="list-group uploadFileList">
    @foreach($textfiles as $file)
        <li class="list-group-item" id="{{$file->getFileId()}}" data-file-id="{{$file->getFileId()}}">
            <span class="text-center fileIconCOnt">
                <img src="/assets/img/fileUpload/txtIcon.png">
            </span>
            <span class="deleteFile">
                <a title="Delete file"  class="deleteFileLink" href="files/delete/{{$file->getFileName()}}"><img class="deleteFile" src="/assets/img/fileUpload/deleteFile.png"></a>
            </span>
            <a class="fileNameText" target="_blank" data-img-name="{{$file->getFileName()}}" href="files/download/{{$file->getFileName()}}">{{$file->getFileName()}}</a>
            <span class="fileDescription">
                <span class="descriptionOfFIle">
                    {{$file->getFileDescription()}}
                </span>
                <a title="Edit description" class="editFileLink" id="{{$file->getFileId()}}">
                    <img class="editFile" src="/assets/img/fileUpload/editFile.png">
                </a>
            </span>

            <button id="{{$file->getFileId()}}" class="chooseFileBtn" essayid="" scholarshipid="" fileid="{{$file->getFileId()}}">Choose</button>

        </li>
    @endforeach
</ul>
<div class="addItHere"></div>