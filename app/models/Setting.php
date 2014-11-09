<?php

class Setting{
    public static function getName()
    {
        return DB::table('setting')->where('key', '=', 'sitename')->first()->value;
    }
    public static function getUUID()
    {
        return DB::table('setting')->where('key', '=', 'uuid')->first()->value;
    }
    public static function getMajor()
    {
        return DB::table('setting')->where('key', '=', 'beaconmajor')->first()->value;
    }
    public static function getMinor()
    {
        return DB::table('setting')->where('key', '=', 'beaconminor')->first()->value;
    }
}
