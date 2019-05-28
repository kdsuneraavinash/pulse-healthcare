import 'package:flutter/material.dart';
import 'package:pulse_healthcare/logic/data/doctor.dart';
import 'package:pulse_healthcare/logic/data/timeline_entry.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:pulse_healthcare/logic/api_controller/api_functions.dart';

typedef Future<String> CommandCallback({
  String apiLink,
  Function ifOkay,
  Function ifError,
});

class APIController extends APIFunctions with ChangeNotifier {
  /// is some api call pending (Api call started, but not ended)
  bool _pending;

  /// is all user data (username/password) retrieved from shared prefs
  bool _userDataRetrieved;

  get userDataRetrieved => _userDataRetrieved ?? false;
  get authorized => user.authorized ?? false;
  get pending => _pending ?? false;
  List<TimelineEntry> get timeline => user.timeline ?? [];

  String get userId => user.userId ?? APIFunctions.EMPTY;
  String get name => user.name ?? APIFunctions.EMPTY;
  String get nic => user.nic ?? APIFunctions.EMPTY;
  String get email => user.email ?? APIFunctions.EMPTY;
  String get phoneNumber => user.phoneNumber ?? APIFunctions.EMPTY;
  String get address => user.address ?? APIFunctions.EMPTY;

  APIController({website = APIFunctions.PC_IP}) : super(website) {
    _pending = false;
    _userDataRetrieved = false;
  }

  setPending(bool v) {
    _pending = v;
    notifyListeners();
  }

  Future<String> loginAndGetAllData(String username, String password) async {
    setPending(true);
    String data = await login(username, password);
    if (data == null) {
      data = await getUserData();
      if (data == null) {
        data = await getTimelineData();
        if (data == null) {
          // Password Username correct, must save
          SharedPreferences prefs = await SharedPreferences.getInstance();
          await prefs.setString('username', username);
          await prefs.setString('password', password);
          setPending(false);
          return null;
        }
      }
    }
    setPending(false);
    return data;
  }

  Future<Map<String, String>> setPreviousUsernameAndPassword() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    String username = prefs.getString('username');
    String password = prefs.getString('password');

    _userDataRetrieved = true;
    if (username != null && password != null) {
      notifyListeners();
      return {'username': username, 'password': password};
    }
    notifyListeners();
    return null;
  }

  Future<String> logout() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.remove('username');
    await prefs.remove('password');
    return super.logout();
  }

  Future<List<Doctor>> getSearchResults(String searchTerm) async {
    return await super.getSearchResults(searchTerm);
  }
}
