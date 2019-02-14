import time
from watchdog.observers import Observer
from watchdog.events import FileSystemEventHandler
import css_html_js_minify
import os
import sys
import re
import shutil
import jinja2


class Functions:
    @staticmethod
    def block_printing(func, *args, **kwargs):
        sys.stdout = open(os.devnull, 'w')
        ret = func(*args, **kwargs)
        sys.stdout = sys.__stdout__
        return ret

    @staticmethod
    def _map_file_content(file, ext):
        if ext != ".html":
            return

        ConsoleController.print_log("Preprocessing {}".format(file))
        content = Functions._get_file_content(file)

        try:
            template = jinja2.Environment(loader=jinja2.FileSystemLoader(
                "src"), extensions=['jinja2.ext.do']).from_string(content)
            result = template.render()
        except Exception as e:
            ConsoleController.print_error("{} : {} ({})".format(
                file, str(e).capitalize(), type(e).__name__))
            result = content

        with open(file.replace(".twig", ""), "w") as fw:
            fw.write(result)

    @staticmethod
    def _get_file_content(file):
        content = ""
        try:
            with open(file, "r") as fr:
                content = fr.read()
        except:
            ConsoleController.print_error("{} reading failed...".format(file))
            content = ""
        return content

    # @staticmethod
    # def html_function(content):
    #     module = preppy.getModule(content)
    #     html = mymodule.get(name, sex)
    #     # pattern = r"(<\?\?)(.*)(\?\?>)"
    #     # replacables = re.findall(pattern, content)
    #     # for pre, x, post in replacables:
    #     #     tmp = Functions._get_file_content(x.strip())
    #     #     whole = r"({}(.*){}(.*){})".format(pre, x, post)
    #     #     content = re.sub(whole, tmp, content)
    #     # return content

    @staticmethod
    def css_function(content):
        return content

    @staticmethod
    def js_function(content):
        return content

    @staticmethod
    def found_html(file):
        Functions._map_file_content(file, ".html")
        # Functions.block_printing(
        #     css_html_js_minify.process_single_html_file, file, overwrite=True)

    @staticmethod
    def found_css(file):
        Functions._map_file_content(file, ".css")
        Functions.block_printing(
            css_html_js_minify.process_single_css_file, file, overwrite=True)

    @staticmethod
    def found_js(file):
        Functions._map_file_content(file, ".js")
        Functions.block_printing(
            css_html_js_minify.process_single_js_file, file, overwrite=True)

    @staticmethod
    def found_other(file):
        pass


class ConsoleController:
    @staticmethod
    def print_error(msg):
        print("* {}".format(msg))

    @staticmethod
    def print_log(msg):
        print("- {}".format(msg))

    @staticmethod
    def print_header():
        print(r"""
____________ _________________ _____ _____   _    _  ___ _____ _____  _   _  ___________ 
| ___ \ ___ \  ___| ___ \ ___ \  _  /  __ \ | |  | |/ _ \_   _/  __ \| | | ||  ___| ___ \
| |_/ / |_/ / |__ | |_/ / |_/ / | | | /  \/ | |  | / /_\ \| | | /  \/| |_| || |__ | |_/ /
|  __/|    /|  __||  __/|    /| | | | |     | |/\| |  _  || | | |    |  _  ||  __||    / 
| |   | |\ \| |___| |   | |\ \\ \_/ / \__/\ \  /\  / | | || | | \__/\| | | || |___| |\ \ 
\_|   \_| \_\____/\_|   \_| \_|\___/ \____/  \/  \/\_| |_/\_/  \____/\_| |_/\____/\_| \_|
""")


class DistController:
    @staticmethod
    def _copytree(src, dst, symlinks=False, ignore=None):
        """ https://stackoverflow.com/questions/1868714/how-do-i-copy-an-entire-directory-of-files-into-an-existing-directory-using-pyth """
        for item in os.listdir(src):
            s = os.path.join(src, item)
            d = os.path.join(dst, item)
            if os.path.isdir(s):
                if not os.path.exists(d):
                    os.mkdir(d)
                DistController._copytree(s, d, symlinks, ignore)
            else:
                shutil.copy2(s, d)

    @staticmethod
    def copy_all_to_dist():
        try:
            DistController._copytree("src", "dist")
        except Exception as e:
            ConsoleController.print_error(
                "Unexpected Error when copying src directory: {}".format(e))

    @staticmethod
    def walk_directory():
        for dirpath, _, fnames in os.walk("dist"):
            for f in fnames:
                if f.endswith(".html.twig"):
                    Functions.found_html(os.path.join(dirpath, f))
                elif f.endswith(".css"):
                    Functions.found_css(os.path.join(dirpath, f))
                elif f.endswith(".js"):
                    Functions.found_js(os.path.join(dirpath, f))
                else:
                    Functions.found_other(os.path.join(dirpath, f))


class Watcher:
    SRC_DIRECTORY = "src"

    def __init__(self):
        ConsoleController.print_log(
            "Starting File Watcher on {}".format(self.SRC_DIRECTORY))
        self.observer = Observer()
        self.dist_controller = DistController()

    def run(self):
        event_handler = Handler()
        self.observer.schedule(
            event_handler, self.SRC_DIRECTORY, recursive=True)
        self.observer.start()
        ConsoleController.print_log("Started File Watcher...")
        try:
            while True:
                time.sleep(100)
        except:
            ConsoleController.print_log("Exiting File Watcher...")
            self.observer.stop()

        self.observer.join()


class Handler(FileSystemEventHandler):
    @staticmethod
    def on_any_event(event):
        print("%s '%s' : Processing..." %
              (event.event_type, event.src_path))
        DistController.copy_all_to_dist()
        DistController.walk_directory()
        print("Ready...")


if __name__ == '__main__':
    ConsoleController.print_header()
    Watcher().run()