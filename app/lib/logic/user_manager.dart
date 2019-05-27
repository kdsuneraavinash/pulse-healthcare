import 'package:flutter/material.dart';
import 'package:pulse_healthcare/logic/timeline_entry.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:pulse_healthcare/logic/user_functions.dart';

const String PC_IP = "10.0.2.2:8000";
const String EMPTY = "-";

typedef Future<String> CommandCallback({
  String apiLink,
  Function ifOkay,
  Function ifError,
});

class UserManager extends UserFunctions with ChangeNotifier {
  bool _pending; // is some action pending

  bool _userDataRetrieved;

  String usernameText;
  String passwordText;

  get userDataRetrieved => _userDataRetrieved;

  get authorized => user.authorized ?? false;
  get pending => _pending ?? false;
  List<TimelineEntry> get timeline => user.timeline ?? [];

  String get userId => user.userId ?? EMPTY;
  String get name => user.name ?? EMPTY;
  String get nic => user.nic ?? EMPTY;
  String get email => user.email ?? EMPTY;
  String get phoneNumber => user.phoneNumber ?? EMPTY;
  String get address => user.address ?? EMPTY;

  UserManager({website = PC_IP}) : super(website) {
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
}
