/*  ----------------------------------------------------------------------------
    tagDiv magic cache - object (static)
 */
var td_local_cache = {
    data: {},
    remove: function (resurce_id) {
        delete td_local_cache.data[resurce_id];
    },
    exist: function (resurce_id) {
        return td_local_cache.data.hasOwnProperty(resurce_id) && td_local_cache.data[resurce_id] !== null;
    },
    get: function (resurce_id) {
        return td_local_cache.data[resurce_id];
    },
    set: function (resurce_id, cachedData) {
        td_local_cache.remove(resurce_id);
        td_local_cache.data[resurce_id] = cachedData;
    }
};