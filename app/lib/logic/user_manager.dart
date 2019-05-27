import 'package:flutter/material.dart';
import 'package:pulse_healthcare/logic/timeline_entry.dart';
import 'package:pulse_healthcare/logic/user.dart';
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

  get authorized => user.authorized ?? false;
  get pending => _pending ?? false;
  List<TimelineEntry> get timeline => user.timeline ?? [];

  get userId => user.userId ?? EMPTY;
  get name => user.name ?? EMPTY;
  get nic => user.nic ?? EMPTY;
  get email => user.email ?? EMPTY;
  get phoneNumber => user.phoneNumber ?? EMPTY;
  get address => user.address ?? EMPTY;

  UserManager({website = PC_IP}) : super(website) {
    _pending = false;
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
          setPending(false);
          return null;
        }
      }
    }
    setPending(false);
    return data;
  }
}
