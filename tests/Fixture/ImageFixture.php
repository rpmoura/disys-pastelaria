<?php

namespace Tests\Fixture;

class ImageFixture
{
    public static function getImageBase64Encoded(): string
    {
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJAAAABkCAYAAABpRbm3AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABhaVRYdFNuaXBNZXRhZGF0YQAAAAAAeyJjbGlwUG9pbnRzIjpbeyJ4IjowLCJ5IjowfSx7IngiOjE0NCwieSI6MH0seyJ4IjoxNDQsInkiOjEwMH0seyJ4IjowLCJ5IjoxMDB9XX121LogAAABD0lEQVR4Xu3SMQHAMAzAsGwswh9o9wxB/UqPCfjZ3TNw6f0LVwxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiCCmQ+I/wIS7la5/wAAAABJRU5ErkJggg==';
    }

    public static function getJustContentFromImageBase64Encoded(): string
    {
        return 'iVBORw0KGgoAAAANSUhEUgAAAJAAAABkCAYAAABpRbm3AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABhaVRYdFNuaXBNZXRhZGF0YQAAAAAAeyJjbGlwUG9pbnRzIjpbeyJ4IjowLCJ5IjowfSx7IngiOjE0NCwieSI6MH0seyJ4IjoxNDQsInkiOjEwMH0seyJ4IjowLCJ5IjoxMDB9XX121LogAAABD0lEQVR4Xu3SMQHAMAzAsGwswh9o9wxB/UqPCfjZ3TNw6f0LVwxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiASA5EYiMRAJAYiMRCJgUgMRGIgEgORGIjEQCQGIjEQiYFIDERiIBIDkRiIxEAkBiIxEImBSAxEYiCCmQ+I/wIS7la5/wAAAABJRU5ErkJggg==';
    }
}
